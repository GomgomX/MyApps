<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Stripe\Stripe;
use App\Models\Shop;
use App\Models\Player;
use App\Models\Account;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\TransactionProcessed;
use Stripe\Exception\ApiErrorException;
use App\Contracts\UpdateProfitInterface;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    protected $updateProfitService;

    public function __construct(UpdateProfitInterface $profit) {
        parent::__construct();
        
        $this->updateProfitService = $profit;
    }

    public function getShopOfferItems() {
        $offer_list = Shop::where('active', 0)->get();
		$i_item = 0;
		$i_shield = 0;
		$i_mage = 0;
		$i_knight = 0;
		$i_weapon = 0;
		$i_paladin = 0;
		$i_container = 0;
        $offer_array = ['item' => [], 'mage' => [], 'paladin' => [], 'knight' => [], 'weapon' => [], 'shield' => [], 'container' => []];
		foreach($offer_list as $offer)
		{
			if($offer->offer_type == 'item')
			{
				$offer_array['item'][$i_item]['id'] = $offer->id;
				$offer_array['item'][$i_item]['item_id'] = $offer->itemid1;
				$offer_array['item'][$i_item]['item_count'] = $offer->count1;
				$offer_array['item'][$i_item]['points'] = $offer->points;
				$offer_array['item'][$i_item]['description'] = $offer->offer_description;
				$offer_array['item'][$i_item]['name'] = $offer->offer_name;
				$i_item++;
			}
    		elseif($offer->offer_type == 'shield')
    		{
      			$offer_array['shield'][$i_shield]['id'] = $offer->id;
      			$offer_array['shield'][$i_shield]['item_id'] = $offer->itemid1;
      			$offer_array['shield'][$i_shield]['item_count'] = $offer->count1;
      			$offer_array['shield'][$i_shield]['points'] = $offer->points;
      			$offer_array['shield'][$i_shield]['description'] = $offer->offer_description;
      			$offer_array['shield'][$i_shield]['name'] = $offer->offer_name;
      			$i_shield++;
    		}
    		elseif($offer->offer_type == 'mage')
    		{
      			$offer_array['mage'][$i_mage]['id'] = $offer->id;
      			$offer_array['mage'][$i_mage]['item_id'] = $offer->itemid1;
      			$offer_array['mage'][$i_mage]['item_count'] = $offer->count1;
      			$offer_array['mage'][$i_mage]['points'] = $offer->points;
      			$offer_array['mage'][$i_mage]['description'] = $offer->offer_description;
      			$offer_array['mage'][$i_mage]['name'] = $offer->offer_name;
      			$i_mage++;
    		}
    		elseif($offer->offer_type == 'knight')
    		{
      			$offer_array['knight'][$i_knight]['id'] = $offer->id;
      			$offer_array['knight'][$i_knight]['item_id'] = $offer->itemid1;
      			$offer_array['knight'][$i_knight]['item_count'] = $offer->count1;
      			$offer_array['knight'][$i_knight]['points'] = $offer->points;
      			$offer_array['knight'][$i_knight]['description'] = $offer->offer_description;
      			$offer_array['knight'][$i_knight]['name'] = $offer->offer_name;
      			$i_knight++;
    		}
    		elseif($offer->offer_type == 'weapon')
    		{
      			$offer_array['weapon'][$i_weapon]['id'] = $offer->id;
      			$offer_array['weapon'][$i_weapon]['item_id'] = $offer->itemid1;
      			$offer_array['weapon'][$i_weapon]['item_count'] = $offer->count1;
      			$offer_array['weapon'][$i_weapon]['points'] = $offer->points;
      			$offer_array['weapon'][$i_weapon]['description'] = $offer->offer_description;
      			$offer_array['weapon'][$i_weapon]['name'] = $offer->offer_name;
      			$i_weapon++;
    		}
    		elseif($offer->offer_type == 'paladin')
    		{
      			$offer_array['paladin'][$i_paladin]['id'] = $offer->id;
      			$offer_array['paladin'][$i_paladin]['item_id'] = $offer->itemid1;
      			$offer_array['paladin'][$i_paladin]['item_count'] = $offer->count1;
      			$offer_array['paladin'][$i_paladin]['points'] = $offer->points;
      			$offer_array['paladin'][$i_paladin]['description'] = $offer->offer_description;
      			$offer_array['paladin'][$i_paladin]['name'] = $offer->offer_name;
      			$i_paladin++;
    		}
			elseif($offer->offer_type == 'container')
			{
				$offer_array['container'][$i_container]['id'] = $offer->id;
				$offer_array['container'][$i_container]['container_id'] = $offer->itemid1;
				$offer_array['container'][$i_container]['container_count'] = $offer->count1;
				$offer_array['container'][$i_container]['item_id'] = $offer->itemid2;
				$offer_array['container'][$i_container]['item_count'] = $offer->count2;
				$offer_array['container'][$i_container]['points'] = $offer->points;
				$offer_array['container'][$i_container]['description'] = $offer->offer_description;
				$offer_array['container'][$i_container]['name'] = $offer->offer_name;
				$i_container++;
			}
		}

        return $offer_array;
    }

    public function getItemByID($id)
	{
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ]);

        if($validator->fails()) {
            return 'Please <a href="/shop/shopoffer">select item</a> first.';
        }

		$data = Shop::where('id', $id)->where('active', 0)->first();
        if($data) {
            $userPremiumPoints = auth()->guard('account')->user()->premium_points;
            if($userPremiumPoints >= $data->points) {
                $offer = [];
                if(in_array($data['offer_type'], array("item", "mage", "paladin", "knight", "weapon", "shield")))
                {
                    $offer['id'] = $data->id;
                    $offer['type'] = $data->offer_type;
                    $offer['item_id'] = $data->itemid1;
                    $offer['item_count'] = $data->count1;
                    $offer['points'] = $data->points;
                    $offer['description'] = $data->offer_description;
                    $offer['name'] = $data->offer_name;
                }
                elseif($data->offer_type == 'container')
                {
                    $offer['id'] = $data->id;
                    $offer['type'] = $data->offer_type;
                    $offer['item_id'] = $data->itemid1;
                    $offer['item_count'] = $data->count1;
                    $offer['container_id'] = $data->itemid2;
                    $offer['container_count'] = $data->count2;
                    $offer['points'] = $data->points;
                    $offer['description'] = $data->offer_description;
                    $offer['name'] = $data->offer_name;
                }
                return $offer;
            } else {
                return 'For this item you need <b>'.$data->points.'</b> points. '.($userPremiumPoints > 0 ? 'You have only <b>'.$userPremiumPoints.'</b> premium points' : 'You don\'t have premium points').'. Please <a href="/shop/shopoffer">select other item</a> or buy premium points.';
            }
        } else {
            return 'Offer with ID <b>'.$id.'</b> doesn\'t exist. Please <a href="/shop/shopoffer">select item</a> again.';
        }
	}

    public function shopOffer() {
        return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'offer_list' => $this->getShopOfferItems()]);
    }

    public function itemOffers() {
        return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'offer_list' => $this->getShopOfferItems(), 'action' => 'item']);
    }

    public function mageOffers() {
        return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'offer_list' => $this->getShopOfferItems(), 'action' => 'mage']);
    }

    public function paladinOffers() {
        return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'offer_list' => $this->getShopOfferItems(), 'action' => 'paladin']);
    }

    public function knightOffers() {
        return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'offer_list' => $this->getShopOfferItems(), 'action' => 'knight']);
    }

    public function weaponOffers() {
        return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'offer_list' => $this->getShopOfferItems(), 'action' => 'weapon']);
    }

    public function shieldOffers() {
        return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'offer_list' => $this->getShopOfferItems(), 'action' => 'shield']);
    }

    public function containerOffers() {
        return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'offer_list' => $this->getShopOfferItems(), 'action' => 'container']);
    }

    public function buyOffer(Request $request) {
        $offer = $this->getItemByID($request->buy_id);
        if(is_array($offer)) {
            return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'action' => 'select_player', 'buy_offer' => $offer]);
        }

        return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'action' => 'select_player', 'errormessage' => $offer]);
    }

    public function selectPlayer(Request $request) {
        $offer = $this->getItemByID($request->buy_id);
        if(!is_array($offer)) {
            return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'action' => 'select_player', 'errormessage' => $offer]);
        }

        $validation = [
            'buy_name' => ['required', 'regex:/^[\pL\s]+$/u', 'min:3', 'max:29', Rule::exists('players', 'name')]
        ];

        if(empty($request->buy_from))
        {
            $request['buy_from'] = 'Anonymous';
        } else {
            $request['buy_from'] = stripslashes(ucwords(strtolower(trim($request->buy_from))));
            $validation['buy_from'] = ['regex:/^[\pL\s]+$/u'];
        }

        $request['buy_name'] = stripslashes(ucwords(strtolower(trim($request->buy_name))));
        $validator = Validator::make($request->all(), $validation, [
            'buy_name.required' => '<b>To player</b> field is required.',
            'buy_name.regex' => '<b>To player</b> field is invalid.',
            'buy_name.min' => '<b>To player</b> field must be at least :min characters.',
            'buy_name.max' => '<b>To player</b> field must be at most :max characters.',
            'buy_name.exists' => 'Player <b>'.$request['buy_name'].'</b> does not exist.',
            'buy_from.regex' => '<b>From</b> field is invalid.'
        ]);

        if($validator->fails()) {
            return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'action' => 'select_player', 'buy_offer' => $offer, 'errors' => $validator->messages()]);
        }
        
        $request->session()->put('variables', encrypt(['buy_offer' => $offer, 'data' => ['buy_from' => $request->buy_from, 'buy_name' => $request->buy_name]]));

        return redirect('/shop/shopoffer/confirm');
    }

    public function confirmTransaction(Request $request) {
        if(!$request->session()->has('variables')) {
            return redirect('/shop/shopoffer');
        }

        $variables = decrypt($request->session()->get('variables'));

        return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'action' => 'confirm_transaction', 'buy_offer' => $variables['buy_offer'], 'data' => $variables['data']]);
    }

    public function addItem(Request $request) {
        if(!$request->session()->has('variables')) {
            return redirect('/shop/shopoffer');
        }

        $variables = decrypt($request->session()->get('variables'));
        $offer = $this->getItemByID($variables['buy_offer']['id']);
        if(!is_array($offer)) {
            $request->session()->forget('variables');
            return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'action' => 'select_player', 'errormessage' => $offer]);
        }

        $validation = [
            'buy_name' => ['required', 'regex:/^[\pL\s]+$/u', 'min:3', 'max:29', Rule::exists('players', 'name')]
        ];

        if(empty($variables['data']['buy_from']))
        {
            $variables['data']['buy_from'] = 'Anonymous';
        } else {
            $variables['data']['buy_from'] = stripslashes(ucwords(strtolower(trim($variables['data']['buy_from']))));
            $validation['buy_from'] = ['regex:/^[\pL\s]+$/u'];
        }

        $variables['data']['buy_name'] = stripslashes(ucwords(strtolower(trim($variables['data']['buy_name']))));
        $validator = Validator::make(['buy_name' => $variables['data']['buy_name'], 'buy_from' => $variables['data']['buy_from']], $validation, [
            'buy_name.required' => '<b>To player</b> field is required.',
            'buy_name.regex' => '<b>To player</b> field is invalid.',
            'buy_name.min' => '<b>To player</b> field must be at least :min characters.',
            'buy_name.max' => '<b>To player</b> field must be at most :max characters.',
            'buy_name.exists' => 'Player <b>'.$variables['data']['buy_name'].'</b> does not exist.',
            'buy_from.regex' => '<b>From</b> field is invalid.'
        ]);

        if($validator->fails()) {
            return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'action' => 'select_player', 'buy_offer' => $offer, 'errors' => $validator->messages()]);
        }

        $player = Player::where('name', $variables['data']['buy_name'])->first();
        if($offer['type'] == 'container')
        {
            $values = [
                'account_id' => $player->account_id,
                'player_id' => $player->id,
                'type' => 'login',
                'action' => 'give_item',
                'param1' => $offer['item_id'],
                'param2' => $offer['item_count'],
                'param3' => $offer['container_id'],
                'param4' => $offer['container_count'],
                'param5' => 'container',
                'param6' => $offer['name'],
                'param7' => '',
                'delete_it' => 1
            ];

            $lastInsertId = DB::table('z_ots_comunication')->insertGetId($values);
        } else {
            $values = [
                'account_id' => $player->account_id,
                'player_id' => $player->id,
                'type' => 'login',
                'action' => 'give_item',
                'param1' => $offer['item_id'],
                'param2' => $offer['item_count'],
                'param3' => '',
                'param4' => '',
                'param5' => 'item',
                'param6' => $offer['name'],
                'param7' => '',
                'delete_it' => 1
            ];

            $lastInsertId = DB::table('z_ots_comunication')->insertGetId($values);
        }
        
        $loggedInUser = auth()->guard('account')->user();
        $loggedInUser->premium_points = $loggedInUser->premium_points-$offer['points'];
        $loggedInUser->save();
        
        $values = [
            'id' => $lastInsertId,
            'to_name' => $player->name, 
            'to_account' => $player->account_id,
            'from_nick' => $variables['data']['buy_from'],
            'from_account' => $loggedInUser->id,
            'price' => $offer['points'],
            'offer_id' => $offer['id'],
            'trans_state' => 'wait',
            'trans_start' => time(),
            'trans_real' => 0,
            'offer_name' => $offer['name']
        ];

        DB::table('z_shop_history_item')->insert($values);

        $this->updateProfitService->updateProfit(0, $offer['points']);

        $request->session()->forget('variables');

        return view('shop.shopoffer', ['pageTitle' => 'Shop Offer', 'subtopic' => 'shopoffer', 'action' => 'item_added', 'buy_offer' => $offer, 'player_name' => $player->name]);
    }

    public function transactionHistory() {
        $loggedInAccount = auth()->guard('account')->user();
        $items_history_received = DB::table('z_shop_history_item')->where('to_account', $loggedInAccount->id)->OrWhere('from_account', $loggedInAccount->id)->get();
        return view('shop.transactionhistory', ['pageTitle' => 'Transaction History', 'subtopic' => 'transactionhistory', 'items_history_received' => $items_history_received, 'loggedInAccount' => $loggedInAccount]);
    }

    public function buyPoints() {
        return view('shop.buypoints', ['pageTitle' => 'Buy Points', 'subtopic' => 'buypoints']);
    }

    public function paypal() {
        return view('shop.paypal', ['pageTitle' => 'PayPal', 'subtopic' => 'buypoints', 'header' => 'paypal']);
    }

    public function stripe() {
        return view('shop.stripe', ['pageTitle' => 'Buy Points', 'subtopic' => 'buypoints']);
    }

    public function stripeSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        if(empty($sessionId)) {
            return view('shop.stripe', ['pageTitle' => 'Buy Points', 'subtopic' => 'buypoints', 'errormessage' => 'No transaction in progress.']);
        }

        $session = null;
        try {
            Stripe::setApiKey(config('app.stripe.secret'));

            $session = Session::retrieve($sessionId);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Success method: '.$e->getMessage());

            return view('shop.stripe', ['pageTitle' => 'Buy Points', 'subtopic' => 'buypoints', 'errormessage' => 'No transaction in progress.']);
        } catch (\Exception $e) {
            Log::error('Stripe Success method: '.$e->getMessage());

            return view('shop.stripe', ['pageTitle' => 'Buy Points', 'subtopic' => 'buypoints', 'errormessage' => 'No transaction in progress.']);
        }

        if($session->status !== 'complete') {
            Log::error('Stripe transaction incomplete: '.$sessionId);

            return view('shop.stripe', ['pageTitle' => 'Buy Points', 'subtopic' => 'buypoints', 'errormessage' => 'Transaction not completed.']);
        }

        $request->session()->forget('cancel_in_progress_transaction');

        $transaction = DB::table('stripe')->where('session_id', $sessionId)->first();
        if($transaction && $transaction->webhook) {
            $request->session()->put('transaction', ['points' => $transaction->points, 'amount' => number_format((float) $transaction->amount, 2, '.', '')]);
            return redirect()->route('added.via.webhook');
        }

        $logFile = storage_path('logs').'/stripe/'.$sessionId.'.log';
        if(!file_exists($logFile))
        {
            $amount = $session->amount_subtotal/100;
            $formatedAmount = number_format((float) $amount, 2, '.', '');
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

            file_put_contents($logFile, date("[d/m/Y g:i:s (A)]")."\r\nAccountID: ".$accountId." (".($account ? "Valid" : "Invalid").")\r\nE-Mail: ".$customerEmail."\r\nAmount: ".$formatedAmount." USD\r\nPoints: ".$points);

            $values = [
                'session_id' => $sessionId,
                'event_id' => 0,
                'webhook' => 0,
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
            
            return view('shop.stripe', ['pageTitle' => 'Buy Points', 'subtopic' => 'buypoints', 'action' => 'success', 'points' => $points, 'amount' => $formatedAmount]);
        } else {
            return view('shop.stripe', ['pageTitle' => 'Buy Points', 'subtopic' => 'buypoints', 'errormessage' => 'This transaction has already been processed.']);
        }
    }

    public function addedViaWebhook(Request $request)
    {
        if(!$request->session()->has('transaction')) {
            return redirect('/shop/buypoints/stripe');
        }

        $transaction = $request->session()->get('transaction');
        $request->session()->forget('transaction');

        return view('shop.stripe', ['pageTitle' => 'Buy Points', 'subtopic' => 'buypoints', 'action' => 'success', 'points' => $transaction['points'], 'amount' => $transaction['amount']]);
    }
    
    public function stripeCancel(Request $request)
    {    
        if(!($request->session()->has('cancel_in_progress_transaction') && $request->session()->get('cancel_in_progress_transaction'))) {
            return view('shop.stripe', ['pageTitle' => 'Buy Points', 'subtopic' => 'buypoints', 'errormessage' => 'No transaction in progress.']);
        }

        $request->session()->forget('cancel_in_progress_transaction');

        return view('shop.stripe', ['pageTitle' => 'Buy Points', 'subtopic' => 'buypoints', 'action' => 'cancel']);
    }
}