<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Account;
use App\Classes\Website;
use App\Classes\Functions;
use Illuminate\Support\Str;
use App\Mail\AccountCreated;
use Illuminate\Http\Request;
use App\Mail\PasswordChanged;
use App\Mail\AccountRegistered;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\Extra\SpoofCheckValidation;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class AccountController extends Controller
{
    public function accountManagement() {
        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement']);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->only(['account', 'password', 'g-recaptcha-response']), [
            'account' => ['required'], 
            'password' => ['required'],
            'g-recaptcha-response' => 'required|captcha'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->onlyInput('remember');
        }

        if(auth()->guard('account')->attempt(['name' => $request['account'], 'password' => $request['password']], isset($request->remember))) {
            request()->session()->regenerate();
            return redirect()->intended('/account/accountmanagement');
        }

        return back()->withErrors('You have supplied the wrong account name or password.')->onlyInput('remember');
    }

    public function logout(Request $request) {
        $loggedInAccount = auth()->guard('account');
        if($loggedInAccount->check()) {
            $url = $request->session()->get('url.intended', '/');
            $loggedInAccount->logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();
        } else {
            $url = '/';
        }

        return redirect($url);
    }

    public function logoutAccount() {
        if(!auth()->guard('account')->check()) {
            return redirect('/');
        }

        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'logoutaccount']);
    }

    public function createCharacter() {
        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'createcharacter']);
    }

    public function storeCharacter(Request $request) {
        $logged_account = auth()->guard('account')->user();
        $accountPlayers = count($logged_account->getPlayers);
        if($accountPlayers >= config('custom.max_players_per_account'))
            return back()->withErrors(['You have too many characters in your account <b>('.$accountPlayers.'/'.config('custom.max_players_per_account').')</b>.'])->withInput();
        
        $request['newcharname'] = stripslashes(ucwords(strtolower(trim($request->newcharname))));
        $validator = Validator::make($request->all(), [
            'newcharname' => ['required', 'min:3', 'max:25', 'regex:/^[\pL\s]+$/u', Rule::unique('players', 'name')],
            'newcharsex' => 'required|numeric',
            'newcharvocation' => 'required|numeric',
            'newchartown' => 'required|numeric'
        ], [
            'newcharname.unique' => 'Name <b>'.$request['newcharname'].'</b> has already been taken.',
            'newcharsex.numeric' => 'Select a valid sex.',
            'newcharvocation.numeric' => 'Select a valid vocation.',
            'newchartown.numeric' => 'Select a valid town.'
        ]);
        
        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $newcharname = $request['newcharname'];
        if(!Functions::newCharacterNameCheck($newcharname))
            return back()->withErrors(['This name contains invalid letters, words or format. Please use only a-Z and space.'])->withInput();

        $sampleChar = Player::where("name", config('custom.newchar_vocations')[(count(config('custom.newchar_vocations')) > 1 && array_key_exists($request->newcharvocation, config('custom.newchar_vocations')) ? $request->newcharvocation : 0)])->first();
        if(!$sampleChar) {
            return back()->withErrors(['Wrong characters configuration. Try again or contact with admin. ADMIN: Edit file config/config.php and set valid characters to copy names. Character to copy <b>'.htmlspecialchars(config('custom.newchar_vocations')[$request->newcharvocation]).'</b> doesn\'t exist.'])->withInput();
        }

        $newCharacter = $sampleChar->replicate();
        if($newCharacter) {
            $newCharacter->name = $newcharname;
            $newCharacter->account_id = $logged_account->id;
            $newCharacter->sex = ($request->newcharsex == "0" || $request->newcharsex == 1 ? $request->newcharsex : 0);
            if($request->newcharsex == "0")
                $newCharacter->looktype = 136;
            $newCharacter->town_id = (count(config('custom.newchar_towns')) > 1 && in_array($request->newchartown, config('custom.newchar_towns')) ? $request->newchartown : config('custom.newchar_towns')[0]);
            $newCharacter->posx = 0;
            $newCharacter->posy = 0;
            $newCharacter->posz = 0;
            $newCharacter->lastlogin = 0;
            $newCharacter->lastlogout = 0;
            $newCharacter->created = time();
            $newCharacter->ip = '';
            $newCharacter->nick_verify = 0;
            $newCharacter->save = true;
            $newCharacter->lastip = 0;
            $newCharacter->direction = 0;
            $newCharacter->hide_char = 0;
            $newCharacter->save();
        } else {
            return back()->withErrors(['Error. Can\'t create character. Probably problem with database. Try again or contact with admin.'])->withInput();
        }

        $sampleCharacterItems = $sampleChar->getItems;
        if($sampleCharacterItems) {
            foreach ($sampleCharacterItems as $sampleCharacterItem){
                $values = [
                    'player_id' => $newCharacter->id,
                    'pid' => $sampleCharacterItem->pid, 
                    'sid' => $sampleCharacterItem->sid,
                    'itemtype' => $sampleCharacterItem->itemtype,
                    'count' => $sampleCharacterItem->count,
                    'attributes' => $sampleCharacterItem->attributes
                ];

                DB::table('player_items')->insert($values);
            }
        }
        
        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'charactercreated', 'newchar_name' => $newCharacter->name]);
    }

    public function changePassword() {
        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'changepassword']);
    }

    public function updatePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'min:6',
                'max:40',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed'
            ],
            'oldpassword' => 'required'
        ], [
            'password.required' => 'Please enter the new password.',
            'password.min' => 'Please enter at least :min characters for the new password.',
            'password.max' => 'Please enter at most :max characters for the new password.',
            'password.regex' => 'The new passwords should contain at least 3 of a-z or A-Z, a number and a special character.',
            'password.confirmed' => 'The new passwords do not match.',
            'oldpassword.required' => 'The old password is required.'
        ]);
        
        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        if(strcmp($request->oldpassword, $request->password) == 0) {
            return back()->withErrors(['New Password cannot be same as your current password.']);
        }

        $loggedInGuard = auth()->guard('account');
        $loggedInAccount = $loggedInGuard->user();
        if(Hash::check($request->oldpassword, $loggedInAccount->password)) {
            $loggedInAccount->forceFill(['password' => $request->password])->setRememberToken(Str::random(60));
            $loggedInAccount->save();
        } else {
            return back()->withErrors(['Current password is incorrect.']);
        }
        
        $mailSent = false;
        if(config('custom.send_emails') && config('custom.send_mail_when_change_password')) {
            $accountPlayers = $loggedInAccount->getPlayers;
            $receiver = $accountPlayers->count() ? $accountPlayers->first()->name : app('server_config')['serverName'].' Player';
            Mail::to([['name' => $receiver, 'email' => $loggedInAccount->email]])->send(new PasswordChanged($request->password));
            $mailSent = true;
        }

        //$loggedInGuard->logoutOtherDevices($request->password);
        $loggedInGuard->login($loggedInAccount, Cookie::has($loggedInGuard->getRecallerName()));

        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'passwordchanged', 'emailSent' => $mailSent]);
    }

    public function deleteCharacter() {
        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'deletecharacter']);
    }

    public function markDeleted(Request $request) {
        $request['delete_name'] = stripslashes(ucwords(strtolower(trim($request->delete_name))));
        $validator = Validator::make($request->all(), [
            'delete_name' => ['required', 'regex:/^[\pL\s]+$/u', 'min:3', 'max:29', Rule::exists('players', 'name')],
            'delete_password' => 'required'
        ], [
            'delete_name.required' => 'Please enter a character name.',
            'delete_name.regex' => 'Please enter a vaild name.',
            'delete_name.min' => 'The name must be at least :min characters.',
            'delete_name.max' => 'The name must be at most :max characters.',
            'delete_name.exists' => 'Character <b>'.$request['delete_name'].'</b> does not exist.',
            'delete_password.required' => 'Please enter the password to your account.',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }
        
        $loggedInAccount = auth()->guard('account')->user();
        $name = $request['delete_name'];
        $player = Player::where("name", $name)->first();
        if($player->account_id != $loggedInAccount->id) {
            return back()->withErrors("Character <b>".htmlspecialchars($name)."</b> is not in your account.");
        }

        if(!Hash::check($request->delete_password, $loggedInAccount->password)) {
            return back()->withErrors("Wrong password to account.");
        }

        if($player->deleted) {
            return back()->withErrors("This character is already deleted.");
        }

        if($player->online) {
            return back()->withErrors("This character is online.");
        }

        $player->deleted = 1;
        $player->save();

        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'markeddeleted', 'deleted_char' => $name]);
    }

    public function undelete($name) {
        $name = stripslashes(ucwords(strtolower(trim($name))));
        $validator = Validator::make(['name' => $name], [
            'name' => ['regex:/^[\pL\s]+$/u', 'min:3', 'max:29', Rule::exists('players', 'name')]
        ], [
            'name.regex' => 'The name is invaild.',
            'name.min' => 'The name must be at least :min characters.',
            'name.max' => 'The name must be at most :max characters.',
            'name.exists' => 'Character <b>'.$name.'</b> does not exist.',
        ]);

        if($validator->fails()) {
            return redirect('/account/accountmanagement')->withErrors($validator)->with('action', 'undeleted');
        }
        
        $loggedInAccount = auth()->guard('account')->user();
        $player = Player::where("name", $name)->first();
        if($player->account_id != $loggedInAccount->id) {
            return redirect('/account/accountmanagement')->withErrors("Character <b>".htmlspecialchars($name)."</b> is not in your account.")->with('action', 'undeleted');
        }

        if(!$player->deleted) {
            return redirect('/account/accountmanagement')->withErrors("This character is not deleted.")->with('action', 'undeleted');
        }

        if($player->online) {
            return redirect('/account/accountmanagement')->withErrors("This character is online.")->with('action', 'undeleted');
        }

        $player->deleted = 0;
        $player->save();

        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'undeleted', 'undeleted_char' => $name]);
    }

    public function serverRules() {
        return view('account.serverrules', ['pageTitle' => 'Account Managment', 'subtopic' => 'serverrules']);
    }

    public function createAccount() {
        return view('account.createaccount', ['pageTitle' => 'Account Managment', 'subtopic' => 'createaccount']);
    }

    public function storeAccount(Request $request) {
        $request['reg_name'] = strtoupper(trim($_POST['reg_name']));
        $validation = [
            'reg_name' => ['required', 'min:4', 'max:32', 'regex:/^[A-Z0-9]+$/', Rule::unique('accounts', 'name')],
            'reg_email' => ['required', Rule::unique('accounts', 'email'), function ($_, $email, $error) {
                $emailValidator = new EmailValidator();
                $multipleValidations = new MultipleValidationWithAnd([
                    new RFCValidation(),
                    new DNSCheckValidation(),
                    new SpoofCheckValidation()
                ]);
                if(!$emailValidator->isValid($email, $multipleValidations)) {
                    $error('Email is invalid.');
                }
            }],
            'password' => ['min:6', 'max:40', 'regex:/^(?=.*[a-zA-Z]{3,})(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-zA-Z\d!@#$%^&*]).+$/', 'confirmed'],
            'reg_country' => ['max:2', 'regex:/^[a-z]+$/'],
            'g-recaptcha-response' => 'required|captcha',
            'rules' => 'accepted'
        ];

        if(!config('custom.create_account_verify_mail')) {
            $validation['password'][] = 'required';
        }

        if(config('custom.select_flag')) {
            $validation['reg_country'][] = 'required';
        }

        $validator = Validator::make($request->all(), $validation, [
            'reg_name.required' => 'Account name is required.',
            'reg_name.min' => 'Account name must be at least :min characters.',
            'reg_name.max' => 'Account name must be at most :max characters.',
            'reg_name.regex' => 'Invalid account name format. Use only A-Z and numbers 0-9.',
            'reg_name.unique' => 'Account name <b>'.$request['reg_name'].'</b> has already been taken.',
            'reg_email.required' => 'Email is required.',
            'reg_email.unique' => 'Email <b>'.$request['reg_email'].'</b> has already been taken.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least :min characters.',
            'password.max' => 'Password must be at least :max characters.',
            'password.regex' => 'Passwords should contain at least 3 of a-z or A-Z, a number and a special character.',
            'password.confirmed' => 'Passwords do not match.',
            'reg_country.max' => 'Please choose a country.',
            'reg_country.regex' => 'Please choose a country.',
            'reg_country.required' => 'Please choose a country.',
            'rules.accepted' => 'Please accept the server rules.'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $flag = config('custom.select_flag') ? $request->reg_country : Website::getCountryCode(long2ip(ip2long($request->ip())));
        $password = $request->password; 
        if(config('custom.create_account_verify_mail'))
        {
            $password = '';
            for($i = 1; $i <= 6; $i++) {
                $password .= mt_rand(0,9);
            }
        }

        $account = Account::create([
            'name' => $request['reg_name'],
            'password' => $password,
            'premdays' => config('custom.newaccount_premdays'),
            'email' => $request->reg_email,
            'blocked' => false,
            'group_id' => 1,
            'page_lastday' => time(),
            'created' => time(),
            'page_access' => 0,
            'flag' => $flag
        ]);
 
        $mailSent = false;
        if(config('custom.send_emails') && config('custom.send_register_email')) {
            try {
                Mail::to([['name' => app('server_config')['serverName'].' Player', 'email' => $account->email]])->send(new AccountCreated($request['reg_name'], $password));
                $mailSent = true;
            } catch (TransportExceptionInterface $e) {
            }
        }
        
        if(!$mailSent && config('custom.send_emails') && config('custom.create_account_verify_mail')) {
            $account->delete();
            return back()->withErrors("An error occorred while sending email! Account was not created. Try again.")->withInput();
        }

        if(config('custom.create_account_needs_verification')) {
            event(new Registered($account));
        } else {
            $account->markEmailAsVerified();
        }

        return view('account.createaccount', ['pageTitle' => 'Account Managment', 'subtopic' => 'createaccount', 'action' => 'accountcreated', 'emailSent' => $mailSent, 'account' => ['account' => $request['reg_name'], 'email' => $request->reg_email, 'password' => $password]]);
    }

    public function registerAccount() {
        $account = auth()->guard('account')->user();
        if(!empty($account->key)) {
            return redirect('/account/accountmanagement')->withErrors("Your account is already registered.")->with('action', 'registered');
        }

        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'registeraccount']);
    }

    public function generateKey(Request $request) {
        $account = auth()->guard('account')->user();
        if(!empty($account->key)) {
            return redirect('/account/accountmanagement')->withErrors("Your account is already registered.")->with('action', 'registered');
        }

        $validator = Validator::make(['password' => $request->password], [
            'password' => 'required'
        ], [
            'delete_password.required' => 'Please enter the password to your account.',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        if(!Hash::check($request->password, $account->password)) {
            return back()->withErrors("Wrong password to account.");
        }

        $acceptedChars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        $max = strlen($acceptedChars)-1;
        $new_rec_key = NULL;
        for($i = 0; $i < 10; $i++) {
            $cnum[$i] = $acceptedChars[mt_rand(0, $max)];
            $new_rec_key .= $cnum[$i];
        }

        $account->key = $new_rec_key;
        $account->save();
 
        $emailSent = false;
        if(config('custom.send_emails') && config('custom.send_mail_when_generate_reckey')) {
            $accountPlayers = $account->getPlayers;
            $receiver = $accountPlayers->count() ? $accountPlayers->first()->name : app('server_config')['serverName'].' Player';
            Mail::to([['name' => $receiver, 'email' => $account->email]])->send(new AccountRegistered($new_rec_key));
            $emailSent = true;
        }

        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'keygenerated', 'emailSent' => $emailSent, 'email' => $account->email, 'key' => $new_rec_key]);
    }

    public function changeInfo(Request $request) {
        $request->session()->put('saveinfo', true);

        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'changeinfo']);
    }

    public function saveInfo(Request $request) {
        if(!($request->session()->has('saveinfo') && $request->session()->get('saveinfo'))) {
            return redirect('/account/accountmanagement');
        }

        $realName = htmlspecialchars(ucwords(strtolower(trim($request->real_name))));
        $loction = htmlspecialchars(ucwords(strtolower(trim($request->location))));
        $validator = Validator::make(['real_name' => $realName, 'location' => $loction, 'country' => $request->country], [
            'real_name' => ['regex:/^[\pL\s]+$/u', 'min:5', 'max:60'],
            'location' => ['regex:/^[A-Za-z, ]+$/', 'min:3', 'max:25'],
            'country' => ['required', 'max:2', 'regex:/^[a-z]+$/']
        ], [
            'real_name.regex' => 'Real name has to be valid.',
            'real_name.min' => 'Real name can\'t be less than :min characters.',
            'real_name.max' => 'Real name can\'t be more than :max characters.',
            'location.regex' => 'Location has to be valid.',
            'location.min' => 'Location can\'t be less than :min characters.',
            'location.max' => 'Location can\'t be more than :max characters.',
            'country.required' => 'Please choose a country.',
            'country.max' => 'Please choose a country.',
            'country.regex' => 'Please choose a country.'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $account = auth()->guard('account')->user();
        $account->rlname = $realName;
        $account->location = $loction;
        $account->flag = $request->country;
        $account->save();

        $request->session()->forget('saveinfo');

        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'saveinfo']);
    }

    public function editCharacter(Request $request, $name) {
        $name = stripslashes(ucwords(strtolower(trim($request->name))));
        $validator = Validator::make(['name' => $name], [
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'min:3', 'max:29', Rule::exists('players', 'name')]
        ], [
            'name.required' => 'Invalid character name.',
            'name.regex' => 'Invalid character name.',
            'name.min' => 'Invalid character name.',
            'name.max' => 'Invalid character name.',
            'name.exists' => 'Character <b>'.$name.'</b> does not exist.',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $loggedInAccount = auth()->guard('account')->user();
        $player = Player::where("name", $name)->first();
        if($player->account_id != $loggedInAccount->id) {
            return back()->withErrors("Character <b>".htmlspecialchars($name)."</b> is not in your account.");
        }

        $request->session()->put('savecharacter', true);

        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'editcharacter', 'player' => $player]);
    }

    public function saveCharacter(Request $request) {
        if(!($request->session()->has('savecharacter') && $request->session()->get('savecharacter'))) {
            return redirect('/account/accountmanagement');
        }

        $name = stripslashes(ucwords(strtolower(trim($request->name))));
        $validator = Validator::make(['name' => $name], [
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'min:3', 'max:29', Rule::exists('players', 'name')]
        ], [
            'name.required' => 'Invalid character name.',
            'name.regex' => 'Invalid character name.',
            'name.min' => 'Invalid character name.',
            'name.max' => 'Invalid character name.',
            'name.exists' => 'Character <b>'.$name.'</b> does not exist.',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $loggedInAccount = auth()->guard('account')->user();
        $player = Player::where("name", $name)->first();
        if($player->account_id != $loggedInAccount->id) {
            return back()->withErrors("Character <b>".htmlspecialchars($name)."</b> is not in your account.");
        }

        $comment = (string) htmlspecialchars(stripslashes(substr(trim($request->comment), 0, config('custom.character_comment_chars_limit'))));

		$player->hide_char = (int) $request->account_visible;
		$player->comment = $comment;
		$player->save();

        $request->session()->forget('savecharacter');

        return view('account.accountmanagement', ['pageTitle' => 'Account Managment', 'subtopic' => 'accountmanagement', 'action' => 'savecharacter']);
    }
}