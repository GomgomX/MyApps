<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Account;
use App\Classes\Website;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\TransactionProcessed;
use App\Contracts\UpdateProfitInterface;
use Illuminate\Support\Facades\Validator;
use Stripe\Exception\SignatureVerificationException;

class TransactionController
{
    protected $updateProfitService;

    public function __construct(UpdateProfitInterface $profit) {
        $this->updateProfitService = $profit;
    }

    public function paypalAddPoints(Request $request) {
        $paypalServerIps =
        [
            '64.4.240.0/21',
            '64.4.248.0/22',
            '66.211.168.0/22',
            '91.243.72.0/23',
            '173.0.80.0/20',
            '185.177.52.0/22',
            '192.160.215.0/24',
            '198.54.216.0/23'
        ];
        
        if(!Website::isIpInRanges($request->ip(), $paypalServerIps))
        {
            Log::error('PayPal IPN verification failed: Unauthorized IP Address');
            
            abort(403, 'Unauthorized IP Address');
        }

        $transaction_id = $request->txn_id;
        $amount = $request->mc_gross;
        $payer_email = $request->payer_email;
        $accountid = $request->custom;

        $validator = Validator::make(['transaction_id' => $transaction_id, 'amount' => $amount, 'payer_email' => $payer_email, 'accountid' => $accountid], [
            'transaction_id' => ['required', 'regex:/^[A-Z0-9]+$/'],
            'amount' => ['required', 'numeric'],
            'payer_email' => ['email'],
            'accountid' => ['numeric']
        ]);

        if($validator->fails()) {
            Log::error('PayPal IPN verification failed: Validation Failed');
            
            abort(403, 'Validation Failed');
        }
        
        $useSandBox = config('custom.paypal_use_sandbox');
        $receiverEmail = $useSandBox ? config('custom.paypal_sandbox_receiver_email') : config('custom.paypal_receiver_email');
        $logFile = storage_path('logs').'/paypal/'.$transaction_id.'.log';
        if(file_exists($logFile) || $request->payment_status != "Completed" || $request->receiver_email != $receiverEmail || $request->mc_currency != config('custom.paypal_currency'))
        {
            Log::error('PayPal IPN verification failed: Second Validation Failed');

            abort(403, 'Validation Failed');
        }
        
        $postFields = 'cmd=_notify-validate&'.http_build_query($request->post());
        
        if($useSandBox) {
            $verifyUrl = config('custom.paypal_sandbox_url');
        } else {
            $verifyUrl = config('custom.paypal_url');
        }

        $ch = curl_init($verifyUrl);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, storage_path('certs/cacert.pem'));
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: PHP-IPN-Verification-Script',
            'Connection: Close',
        ));

        $response = curl_exec($ch);
        if($response === false) {
            $errno = curl_errno($ch);
            $errstr = curl_error($ch);
            curl_close($ch);

            Log::error("cURL error: [$errno] $errstr");

            return response('IPN Verification Failed', 500);
        }
        curl_close($ch);
    
        if($response === 'VERIFIED' || ($useSandBox && $request->has('test_ipn'))) {
            $addpoints = ceil($amount*config('custom.paypal_base_premium_points'));
            $account = Account::find($accountid);
            if(file_put_contents($logFile, date("[d/m/Y g:i:s (A)]").($useSandBox ? " - Sandbox Transaction" : "")."\r\nAccountID: ".$accountid." (".($account ? "Valid" : "Invalid").")\r\nE-Mail: ".$payer_email."\r\nAmount: ".$amount." USD\r\nPoints: ".$addpoints) !== false)
            {
                if($account) {
                    $account->premium_points += $addpoints;
                    $account->save();
                }

                $values = [
                    'transaction_id' => $transaction_id, 
                    'validation' => (bool) $account,
                    'account_id' => $accountid,
                    'email' => $payer_email,
                    'retrieved' => 0,
                    'amount' => $amount,
                    'points' => $addpoints,
                    'inserted' => date("g:i:s (A) | d-m-Y"),
                    'added_at' => Carbon::now()->toDateTimeString()
                ];
        
                DB::table('paypal')->insert($values);

                $this->updateProfitService->updateProfit($amount, 0);
                event(new TransactionProcessed($account, $addpoints, $amount, "PayPal"));
            }

            Log::info('PayPal IPN received and verified'); // '.json_encode($request->post())

            return response('IPN Processed', 200);
        } else {
            Log::error('PayPal IPN verification failed: '.$response);
            
            return response('IPN Verification Failed', 500);
        }
    }

    public function createStripeSession(Request $request) {
        if(!$request->has('price_id')) {
            return redirect('/shop/buypoints/stripe');
            // abort(400);
        }

        try {
            Stripe::setApiKey(config('app.stripe.secret'));
            
            $idempotencyKey = time().'_'.uniqid(); // Str::uuid()->toString();
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $request->price_id,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe-success', [], true).'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe-cancel', [], true),
                'metadata' => [
                    'account_id' => auth()->guard('account')->user()->id,
                ],
            ], [
                'idempotency_key' => $idempotencyKey
            ]);

            $request->session()->put('cancel_in_progress_transaction', true);

            return response()->json(['sessionId' => $session->id]);
        } catch (\Exception $e) {
            return redirect('/shop/buypoints/stripe');
            // abort(500);
        }
    }

    public function stripeWebhook(Request $request)
    {
        $allowedIps = [
            '3.18.12.63',
            '3.130.192.231',
            '13.235.14.237',
            '13.235.122.149',
            '18.211.135.69',
            '35.154.171.200',
            '52.15.183.38',
            '54.88.130.119',
            '54.88.130.237',
            '54.187.174.169',
            '54.187.205.235',
            '54.187.216.72',
        ];

        if(!in_array($request->ip(), $allowedIps)) {
            Log::error('Stripe Webhook verification failed: Unauthorized IP Address');

            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $payload = $request->getContent();
        $signatureHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('app.stripe.webhook_secret');
        $tolerance = 300;

        $event = null;
        try {
            $event = Webhook::constructEvent($payload, $signatureHeader, $endpointSecret, $tolerance);
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe Webhook invalid payload: '.$e->getMessage());

            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe Webhook invalid signature: '.$e->getMessage());

            return response()->json(['error' => 'Invalid signature'], 400);
        }
        
        $webhook = DB::table('stripe_webhooks')->where('event_id', $event->id)->first();
        if(!$webhook) {
            $values = [
                'event_id' => $event->id,
                'event_type' => $event->type,
                'added_at' => Carbon::now()->toDateTimeString()
            ];
    
            DB::table('stripe_webhooks')->insert($values);
            
            sleep(15);
        }

        $pointsAddedByWebhook = false;
        switch($event->type) {
            case 'checkout.session.completed':
                $pointsAddedByWebhook = $this->processWebhookSession($event->data->object, $event->id);
                break;
            default:
                Log::error('Stripe Webhook unhandled event type: '.$event->type);

                return response()->json(['error' => 'Unhandled event type.'], 400);
        }

        if($pointsAddedByWebhook) {
            Log::info('Stripe Webhook received and verified');
        }

        return response()->json(['success' => 'Webhook received successfully.'], 200);
    }

    private function processWebhookSession($session, $eventId)
    {
        if($session->status !== 'complete') {
            Log::error('Stripe Webhook transaction incomplete: '.$session->id);

            return false;
        }

        $logFile = storage_path('logs').'/stripe/'.$session->id.'.log';
        if(file_exists($logFile)) {
            return false;
        }
        
        $amount = $session->amount_subtotal/100;
        $formatedAmount = number_format((float)  $amount, 2, '.', '');
        $points = ceil($amount*125);
        $accountId = $session->metadata->account_id;
        $account = Account::find($accountId);
        if($account) {
            $account->premium_points += $points;
            $account->save();
        }
        
        if(isset($session->customer_email)) {
            $customerEmail = $session->customer_email;
        } elseif(isset($session->customer_details) && isset($session->customer_details->email)) {
            $customerEmail = $session->customer_details->email;
        } else {
            $customerEmail = '';
        }

        $logContent = sprintf("[%s] - Webhook\r\nAccountID: %d (%s)\r\nE-Mail: %s\r\nAmount: %.2f USD\r\nPoints: %d",
            date("d/m/Y g:i:s (A)"),
            $accountId,
            $account ? "Valid" : "Invalid",
            $customerEmail,
            $formatedAmount,
            $points
        );

        file_put_contents($logFile, $logContent);

        $values = [
            'session_id' => $session->id,
            'event_id' => $eventId,
            'webhook' => 1,
            'validation' => (bool) $account,
            'account_id' => $accountId,
            'email' => $customerEmail,
            'retrieved' => 0,
            'amount' => $amount,
            'points' => $points,
            'inserted' => date("g:i:s (A) | d-m-Y"),
            'added_at' => Carbon::now()->toDateTimeString()
        ];

        DB::table('stripe')->insert($values);

        $this->updateProfitService->updateProfit($amount, 0);
        event(new TransactionProcessed($account, $points, $formatedAmount, "Stripe"));

        return true;
    }
}