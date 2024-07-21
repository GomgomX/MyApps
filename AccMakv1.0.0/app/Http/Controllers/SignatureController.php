<?php

namespace App\http\Controllers;

use App\Models\Player;
use Illuminate\Support\Facades\Validator;

class SignatureController {
    public function signature($name) {
        $name = stripslashes(ucwords(strtolower(trim($name))));
        $validator = Validator::make(['name' => $name], [
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'min:3', 'max:29']
        ]);

        if($validator->fails()) {
            return redirect('/community/characters')->withErrors($validator);
        }
        
        $excludedNames = [
            'Account Manager',
            'Sorcerer Sample',
            'Knight Sample',
            'Druid Sample',
            'Paladin Sample'
        ];

        $player = Player::where("name", $name)->whereNotIn('name', $excludedNames)->first();
        if(!$player) {
            return redirect('/community/characters')->withErrors(['search' => 'Character <b>'.$name.'</b> does not exist.']);
        }

        return view('community.signature', ['player' => $player]);
    }
}