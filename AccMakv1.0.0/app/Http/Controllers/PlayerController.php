<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\PlayerDeath;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    public function characters() {
        return view('community.characters', ['pageTitle' => 'Characters', 'subtopic' => 'characters']);
    }

    public function search(Request $request) {
        $request['name'] = stripslashes(ucwords(strtolower(trim($request->name))));
        $validator = Validator::make(['name' => $request->name], [
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'min:3', 'max:29', Rule::exists('players', 'name')]
        ], [
            'name.exists' => 'Character <b>'.$request['name'].'</b> does not exist.'
        ]);

        if($validator->fails()) {
            return redirect('/community/characters')->withErrors($validator)->withInput();
        }
        
        return redirect('/community/characters/'.$request->name);
    }

    public function show($name) {
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

        return view('community.characters', ['pageTitle' => 'Characters', 'subtopic' => 'characters', 'player' => $player]);
    }

    public function online() {
        return view('community.whoisonline', ['pageTitle' => 'Who Is Online', 'subtopic' => 'whoisonline', 'onlinePlayers' => Player::where('online', 1)->order(request('order'))->with('getAccount')->get()]);
    }

    public function highscores() {
        $excludedNames = [
            'Account Manager',
            'Sorcerer Sample',
            'Knight Sample',
            'Druid Sample',
            'Paladin Sample'
        ];

        $query = Player::where('deleted', 0)->whereNotIn('name', $excludedNames);
        $perPage = 100;
        $maxPages = 50;
        $total = min($perPage*$maxPages, $query->count());
        $page = request()->has('page') ? (int) request()->page : 1;
        $page = min($maxPages, $page);
        $highscores = $query->sort(request(['skill', 'vocation']))->with('getAccount')->paginate($perPage, ['*'], 'page', $page, $total)->onEachSide(0)->appends(['skill' => request('skill'), 'vocation' => request('vocation')]);
        return view('community.highscores', ['pageTitle' => 'Highscores', 'subtopic' => 'highscores', 'highscores' => $highscores, 'offset' => max(0, $page-1) * $perPage]);
    }

    public function latestDeaths() {
        $latestDeaths = PlayerDeath::filter(config('custom.latest_deaths_limit'))->orderBy('date', 'DESC')->with('getPlayer', 'getKillers')->get();

        return view('community.latestdeaths', ['pageTitle' => 'Latest Deaths', 'subtopic' => 'latestdeaths', 'latest_deaths' => $latestDeaths]);
    }
}