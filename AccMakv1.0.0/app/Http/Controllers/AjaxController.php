<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Account;
use App\Classes\Functions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\Extra\SpoofCheckValidation;

class AjaxController
{
    public function getPlayers(Request $request)
    {        
        if($request->json('player_action') == 'do')
        {
            $interval = 5;
            $now = time();
            $last = 0;

            if($request->session()->has('player_lastTime')) {
                $last = $request->session()->get('player_lastTime');
            }

            if($now - $last >= $interval) {
                $players = Player::where('online', '1')->count();

                $request->session()->put('player_lastUpdate', $players);
                $request->session()->put('player_lastTime', $now);

                $onlinePlayers = $players > 0 ? $players." Player".($players > 1 ? "s" : "")." Online" : "";
                $jsonString = json_encode(["online" => $onlinePlayers]);
                $filePath = public_path('storage').'/cache/status.json';
                file_put_contents($filePath, $jsonString);

                return response()->json(['players' => $players]);
            } else {
                $players = $request->session()->get('player_lastUpdate');
                return response()->json(['players' => $players]);
            }
        }
    }

    public function getVisitors(Request $request)
    {
        if($request->json('visitor_action') == 'do')
        {
            $interval = 10;
            $now = time();
            $last = 0;

            if($request->session()->has('visitor_lastTime')) {
                $last = $request->session()->get('visitor_lastTime');
            }

            if($now - $last >= $interval) {
                $visitors = count(Cache::get('visitors', [])) ?: 1;

                $request->session()->put('visitor_lastUpdate', $visitors);
                $request->session()->put('visitor_lastTime', $now);

                return response()->json(['visitors' => $visitors]);
            } else {
                $visitors = $request->session()->get('visitor_lastUpdate');
                return response()->json(['visitors' => $visitors]);
            }
        }
    }

    public function checkAccount(Request $request) {
        $accountName = (string) strtoupper(trim($request->query('account')));
        if(empty($accountName))
        {
            return response()->json([
                'message' => 'Please enter an account number.',
            ]);
        }
        
        if(strlen($accountName) < 4)
        {
            return response()->json([
                'message' => 'This account name is too short.',
            ]);
        }

        if(strlen($accountName) > 32)
        {
            return response()->json([
                'message' => 'This account name is too long.',
            ]);
        }

        $tempAccountName = strspn("$accountName", "QWERTYUIOPASDFGHJKLZXCVBNM0123456789");
        if($tempAccountName != strlen($accountName))
        {
            return response()->json([
                'message' => 'Invalid account name format. Use only A-Z and numbers 0-9.',
            ]);
        }

        $account = Account::where('name', $accountName)->first();
        if($account) {
            return response()->json([
                'message' => 'This account name is already used.',
            ]);
        }

        return response()->json([
            'message' => 'Account Name Valid',
        ]);
    }
    
    public function checkEmail(Request $request) {
        $email = trim($request->query('email'));
        if(empty($email))
        {
            return response()->json([
                'message' => 'Please enter your email address.',
            ]);
        }
        
        if(strlen($email) > 255)
        {
            return response()->json([
                'message' => 'This email address is too long.',
            ]);
        }

        $validator = Validator::make(['email' => $email], [
            'email' => [function ($_, $email, $error) {
                $emailValidator = new EmailValidator();
                $multipleValidations = new MultipleValidationWithAnd([
                    new RFCValidation(),
                    new DNSCheckValidation(),
                    new SpoofCheckValidation()
                ]);
                if(!$emailValidator->isValid($email, $multipleValidations)) {
                    $error('This email address is invalid.');
                }
            }]
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => $validator->messages()->first('email'),
            ]);
        }
        
        $account = Account::where('email', $email)->first();
        if($account) {
            return response()->json([
                'message' => 'This email address is already used.',
            ]);
        }

        return response()->json([
            'message' => 'Email Address Valid',
        ]);
    }

    public function checkName(Request $request) {
        $name = (string) stripslashes(ucwords(strtolower(trim($request->query('name')))));
        if(empty($name))
        {
            return response()->json([
                'message' => 'Please enter a name for your character.',
            ]);
        }
        
        if(strlen($name) < 3)
        {
            return response()->json([
                'message' => 'This character name is too short.',
            ]);
        }

        if(strlen($name) > 25)
        {
            return response()->json([
                'message' => 'This character name is too long.',
            ]);
        }

        $tempName = strspn("$name", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM ");
        if($tempName != strlen($name))
        {
            return response()->json([
                'message' => 'This character name is invalid.',
            ]);
        }

        if(!Functions::newCharacterNameCheck($name))
        {
            return response()->json([
                'message' => 'This name contains invalid letters, words or format. Please use only a-Z and space.',
            ]);
        }

        $player = Player::where('name', $name)->first();
        if($player) {
            return response()->json([
                'message' => 'This character name is already used.',
            ]);
        }

        return response()->json([
            'message' => 'Character Name Valid',
        ]);
    }
}