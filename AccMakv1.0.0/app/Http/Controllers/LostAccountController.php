<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Player;
use App\Models\Account;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordChanged;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;

class LostAccountController extends Controller
{
    public function show() {
        return view('account.lostaccount', ['pageTitle' => 'Lost Account', 'subtopic' => 'lostaccount']);
    }

    public function resetPassword(Request $request) {
        $request['name_email'] = stripslashes(trim($request->name_email));
        $validation = [];
        $searchBy = '';
        if(!empty($request['name_email'])) {
            if(!filter_var($request['name_email'], FILTER_VALIDATE_EMAIL)) {
                $validation = ['regex:/^[\pL\s]+$/u', 'min:3', 'max:29', Rule::exists('players', 'name')];
                $searchBy = 'Character';
            } else {
                $validation = ['email', Rule::exists('accounts', 'email')];
                $searchBy = 'Email';
            }
        } else {
            return back()->withErrors("Please enter your character name or email address.")->withInput();
        }

        $validator = Validator::make($request->only('name_email', 'action_type'), [
            'name_email' => $validation,
            'action_type' => 'required'
        ], [
            'name_email.regex' => 'Please enter a vaild character name or email address.',
            'name_email.min' => 'Character name must be at least :min characters.',
            'name_email.max' => 'Character name must be at most :max characters.',
            'name_email.exists' => ($searchBy).' <b>'.$request['name_email'].'</b> does not exist.',
            'action_type.required' => 'Please select a method to reset password'
        ]);
        
        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $account = ($searchBy == "Character" ? Player::where('name', $request['name_email'])->first()->getAccount : Account::where('email', $request['name_email'])->first());
        if(!$account->hasVerifiedEmail()) {
            $eVerifyMessage = ($searchBy == "Character" ? "Email of this character" : "This email address");
            return back()->withErrors($eVerifyMessage.' is not verified.')->withInput();
        }

        if($request->action_type == "reckey") {
            if(empty($account->key)) {
                $rkeyMessage = ($searchBy == "Character" ? "Account of this character has" : "Account that's linked to this email address");
                return back()->withErrors($rkeyMessage.' has no recovery key.')->withInput();
            }

            $passwordResetToken = DB::table('password_reset_tokens')->select('email', 'created_at')->where('email', $account->email)->where('created_at', '<', Carbon::now()->addHour());
            if($passwordResetToken->count()) {
                $minutes = round(Carbon::now()->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $passwordResetToken->first()->created_at)->addHour()));
                return back()->withErrors("A reset password link was already requested that will expire in ".$minutes." minute".($minutes > 1 ? "s" : "").". Check your inbox and click the link.")->withInput();
            }
        }

        if($request->action_type == "email") {
            $request->session()->put('variables', encrypt(['email' => $account->email, 'character' => ($searchBy == "Character" ? ucwords($request['name_email']) : "")]));
            return redirect('/account/lostaccount/emailaddress');
        } else {
            $request->session()->put('variables', encrypt(['account' => $account, 'character' => ($searchBy == "Character" ? ucwords($request['name_email']) : "")]));
            return redirect('/account/lostaccount/recoverykey');
        }
    }

    public function emailAddress(Request $request) {
        if(!$request->session()->has('variables')) {
            return redirect('/account/lostaccount');
        }

        $variables = decrypt($request->session()->get('variables'));

        return view('account.lostaccount', ['pageTitle' => 'Lost Account', 'subtopic' => 'lostaccount', 'action' => 'emailaddress', 'email' => $variables['email'], 'character' => $variables['character']]);
    }

    public function sendResetLink(Request $request) {
        if(!$request->session()->has('variables')) {
            return redirect('/account/lostaccount');
        }

        $variables = decrypt($request->session()->get('variables'));

        $email = $variables['email'];
        $validator = Validator::make(['email' => $email], [
            'email' => ['required', 'email', Rule::exists('accounts', 'email')]
        ], [
            'email.required' => 'Invalid email.',
            'email.email' => 'Invalid email.',
            'email.exists' => 'Invalid email.'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $status = Password::broker('accounts')->sendResetLink(['email' => $email]);
        if($status === Password::RESET_LINK_SENT) {
            $request->session()->forget('variables');
            return view('account.lostaccount', ['pageTitle' => 'Lost Account', 'subtopic' => 'lostaccount', 'action' => 'emailsent', 'email' => $email, 'character' => $variables['character']]);
        } else {
            return back()->withErrors(__($status));
        }
    }

    public function validateToken(string $token, Request $request) {
        return view('account.lostaccount', ['pageTitle' => 'Lost Account', 'subtopic' => 'lostaccount', 'action' => 'validatetoken', 'token' => $token, 'email' => $request->email]);
    }

    public function updatePasswordByEmail(Request $request) {
        $request['email'] = stripslashes(trim($request->email));
        $validator = Validator::make($request->only('password', 'password_confirmation', 'email', 'token'), [
            'email' => ['required', 'email', Rule::exists('accounts', 'email')],
            'password' => [
                'required',
                'min:6',
                'max:40',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed'
            ],
            'token' => 'required'
        ], [
            'email.required' => 'Invalid email address.',
            'email.email' => 'Invalid email address.',
            'email.exists' => 'Invalid email address.',
            'password.required' => 'Please enter the new password.',
            'password.min' => 'Please enter at least :min characters for the new password.',
            'password.max' => 'Please enter at most :max characters for the new password.',
            'password.regex' => 'The new passwords should contain at least 3 of a-z or A-Z, a number and a special character.',
            'password.confirmed' => 'The new passwords do not match.',
            'token.required' => 'Invalid token.'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $status = Password::broker('accounts')->reset($request->only('email', 'password', 'password_confirmation', 'token'),
            function (Account $account, string $password) {
                $account->forceFill(['password' => $password])->setRememberToken(Str::random(60));
                $account->save();
                
                event(new PasswordReset($account));
                
                if(config('custom.send_emails') && config('custom.send_mail_when_change_password')) {
                    $accountPlayers = $account->getPlayers;
                    $receiver = $accountPlayers->count() ? $accountPlayers->first()->name : app('server_config')['serverName'].' Player';
                    Mail::to([['name' => $receiver, 'email' => $account->email]])->send(new PasswordChanged($password));
                }
            }
        );
     
        if($status === Password::PASSWORD_RESET) {
            return view('account.lostaccount', ['pageTitle' => 'Lost Account', 'subtopic' => 'lostaccount', 'action' => 'passwordchanged', 'emailSent' => config('custom.send_emails') && config('custom.send_mail_when_change_password'), 'email' => $request['email']]);
        } else {
            return back()->withErrors(__($status))->withInput();
        }
    }

    public function recoveryKey(Request $request) {
        if(!$request->session()->has('variables')) {
            return redirect('/account/lostaccount');
        }

        $variables = decrypt($request->session()->get('variables'));

        return view('account.lostaccount', ['pageTitle' => 'Lost Account', 'subtopic' => 'lostaccount', 'action' => 'recoverykey', 'account' => $variables['account'], 'character' => $variables['character']]);
    }

    public function checkRecoveryKey(Request $request) {
        if(!$request->session()->has('variables')) {
            return redirect('/account/lostaccount');
        }

        $validator = Validator::make(['rkey' => $request->rkey], [
            'rkey' => ['required', 'min:10', 'max:10', 'regex:/^[A-Z0-9]+$/']
        ], [
            'rkey.required' => 'Please enter a recovery key.',
            'rkey.min' => 'Recovery key must be 10 characters.',
            'rkey.max' => 'Recovery key must be 10 characters.',
            'rkey.regex' => 'Invalid recovery key.'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $variables = decrypt($request->session()->get('variables'));
        $account = Account::where('id', $variables['account']->id)->first();
        if(!$account) {
            $request->session()->forget('variables');
            return redirect('/account/lostaccount')->withErrors('Invalid account.');
        }

        if(empty($account->key)) {
            return back()->withErrors('This account has no recovery key.')->withInput();
        }

        if($account->key != $request['rkey']) {
            return back()->withErrors('Wrong recovery key.')->withInput();
        }

        $passwordResetToken = DB::table('password_reset_tokens')->select('email', 'created_at')->where('email', $account->email)->where('created_at', '<', Carbon::now()->addHour());
        if($passwordResetToken->count()) {
            $minutes = round(Carbon::now()->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $passwordResetToken->first()->created_at)->addHour()));
            return back()->withErrors("A reset password link was already requested that will expire in ".$minutes." minute".($minutes > 1 ? "s" : "").". Check your inbox and click the link.")->withInput();
        }

        $request->session()->put('variables', encrypt(['account' => $account, 'rkey' => $request['rkey']])); 

        return redirect('/account/lostaccount/changepassword');
    }

    public function changePassword() {
        return view('account.lostaccount', ['pageTitle' => 'Lost Account', 'subtopic' => 'lostaccount', 'action' => 'updatepassword']);
    }

    public function updatePassword(Request $request) {
        if(!$request->session()->has('variables')) {
            return redirect('/account/accountmanagement');
        }
        
        $validator = Validator::make(['password' => $request->password, 'password_confirmation' => $request->password_confirmation], [
            'password' => [
                'required',
                'min:6',
                'max:40',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed'
            ]
        ], [
            'password.required' => 'Please enter the new password.',
            'password.min' => 'Please enter at least :min characters for the new password.',
            'password.max' => 'Please enter at most :max characters for the new password.',
            'password.regex' => 'The new passwords should contain at least 3 of a-z or A-Z, a number and a special character.',
            'password.confirmed' => 'The new passwords do not match.'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }
        
        $variables = decrypt($request->session()->get('variables'));
        $account = Account::where('id', $variables['account']->id)->first();
        if(!$account) {
            $request->session()->forget('variables');
            return redirect('/account/lostaccount')->withErrors('Invalid account.');
        }
        
        if(empty($account->key)) {
            return back()->withErrors('This account has no recovery key.');
        }

        if($account->key != $variables['rkey']) {
            return back()->withErrors('Wrong recovery key.');
        }
        
        $passwordResetToken = DB::table('password_reset_tokens')->select('email', 'created_at')->where('email', $account->email)->where('created_at', '<', Carbon::now()->addHour());
        if($passwordResetToken->count()) {
            $minutes = round(Carbon::now()->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s', $passwordResetToken->first()->created_at)->addHour()));
            return back()->withErrors("A reset password link was already requested that will expire in ".$minutes." minute".($minutes > 1 ? "s" : "").". Check your inbox and click the link.")->withInput();
        }

        $request->session()->forget('variables');

        $account->forceFill(['password' => $request->password])->setRememberToken(Str::random(60));
        $account->save();

        event(new PasswordReset($account));
        
        $emailSent = false;
        if(config('custom.send_emails') && config('custom.send_mail_when_change_password')) {
            $accountPlayers = $account->getPlayers;
            $receiver = $accountPlayers->count() ? $accountPlayers->first()->name : app('server_config')['serverName'].' Player';
            Mail::to([['name' => $receiver, 'email' => $account->email]])->send(new PasswordChanged($request->password));
            $emailSent = true;
        }

        return view('account.lostaccount', ['pageTitle' => 'Lost Account', 'subtopic' => 'lostaccount', 'action' => 'passwordchanged', 'emailSent' => $emailSent, 'email' => $account->email]);
    }
}