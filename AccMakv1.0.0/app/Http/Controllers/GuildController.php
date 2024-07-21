<?php

namespace App\Http\Controllers;

use App\Models\Guild;
use App\Models\Player;
use App\Models\GuildRank;
use App\Classes\Functions;
use App\Models\GuildInvite;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GuildController extends Controller {
    public function index() {
        $guilds_list = Guild::orderBy('name', 'ASC')->get();
        return view('community.guilds.index', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guilds_list' => $guilds_list]);
    }

    public function show($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->with('getRanks.getPlayers', 'getInvites.getPlayer')->first();
        if(!$guild) {
            $validator->errors()->add('', 'Guild with ID <b>'.$id.'</b> doesn\'t exist.');
            return view('community.guilds.show', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'errors' => $validator->errors()]);
        }

        return view('community.guilds.show', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild' => $guild]);
    }

    public function create() {
        $loggedInAccount = auth()->guard('account')->user();
        $loggedInAccountPlayers = $loggedInAccount->getPlayers;
        if(count($loggedInAccountPlayers) == 0) {
            return redirect('/community/guilds')->withErrors('There are no characters in your account.');
        }

        $array_of_players_not_in_guild = [];
		foreach($loggedInAccountPlayers as $player)
		{
			if(empty($player->rank_id))
            {
				if($player->level >= config('custom.guild_need_level'))
                {
					if(!config('custom.guild_need_pacc') || $loggedInAccount->premdays > 0)
                    {
						$array_of_players_not_in_guild[] = $player->name;
                    }
                }
            }
		}

        if(count($array_of_players_not_in_guild) == 0) {
            return redirect('/community/guilds')->withErrors('All characters in your account are in guilds or have too low level to create a new guild.');
        }

        return view('community.guilds.create', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'array_of_players_not_in_guild' => $array_of_players_not_in_guild]);
    }

    public function store(Request $request){
        $loggedInAccount = auth()->guard('account')->user();
        $loggedInAccountPlayers = $loggedInAccount->getPlayers;
        if(count($loggedInAccountPlayers) == 0) {
            return redirect('/community/guilds')->withErrors('There are no characters in your account.');
        }

        $array_of_players_not_in_guild = [];
		foreach($loggedInAccountPlayers as $player)
		{
			if(empty($player->rank_id))
            {
				if($player->level >= config('custom.guild_need_level'))
                {
					if(!config('custom.guild_need_pacc') || $loggedInAccount->premdays > 0)
                    {
						$array_of_players_not_in_guild[] = $player->name;
                    }
                }
            }
		}

        if(count($array_of_players_not_in_guild) == 0) {
            return redirect('/community/guilds')->withErrors('All characters in your account are in guilds or have too low level to create a new guild.');
        }
        
	    $request['guild'] = ucwords(trim($request->guild));
        $request['name'] = ucwords(strtolower(trim($request->name)));
        $validator = Validator::make(['guild' => $request->guild, 'name' => $request->name], [
            'guild' => ['required', 'regex:/^[a-zA-Z0-9\s]+$/', 'min:5', 'max:25', Rule::unique('guilds', 'name')],
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'max:25']
        ], [
            'guild.required' => 'Choose a guild name.',
            'guild.regex' => 'Invalid guild name.',
            'guild.min' => 'Guild name must be at least :min characters.',
            'guild.max' => 'Guild name must be at most :max characters.',
            'guild.unique' => 'Guild <b>'.$request->guild.'</b> already exists. Choose another name.',
            'name.required' => 'Select a character',
            'name.regex' => 'Invalid character name',
            'name.max' => 'Character name must be at most :max characters.'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $player = Player::where('name', $request->name)->first();
        if(!$player) {
            return back()->withErrors('Character <b>'.$request->name.'</b> doesn\'t exist.')->withInput();
        }

        if(!in_array($player->name, $array_of_players_not_in_guild)) {
            return back()->withErrors('Character <b>'.htmlspecialchars($player->name).'</b> isn\'t in your account, is already in a guild or lower than level '.config('custom.guild_need_level').'.');
        }

        $time = time();
        $new_guild = new Guild;
		$new_guild->creationdata = $time;
		$new_guild->name = $request->guild;
        $new_guild->ownerid = $player->id;
		$new_guild->description = 'This guild was founded on '.date("F j, Y, g:i a", $time).' by '.$player->name.'.';
		$new_guild->save();
        $guildRanksOrderByLevelDesc = $new_guild->getRanks()->orderBy('level', 'DESC')->get();
		foreach($guildRanksOrderByLevelDesc as $rank)
        {
			if($rank->level == 3)
			{
				$player->rank_id = $rank->id;
				$player->save();
                break;
			}
        }
		
        return view('community.guilds.create', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'action' => 'guild_created', 'guild_id' => $new_guild->id, 'guild_name' => $new_guild->name, 'player_name' => $player->name]);
    }

    public function changeRank(Request $request, $id) {
        $sessionVariables = [];
        if($request->session()->has('variables')) {
            $sessionVariables = decrypt($request->session()->get('variables'));
            $request->session()->forget('variables');
        }

        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->with('getOwner', 'getRanks.getPlayers')->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $rank_list = $guild->getRanks()->orderBy('level', 'DESC')->get();
        $guild_leader = false;
        $guild_vice = false;
        $level_in_guild = null;
        $loggedInAccountPlayers = auth()->guard('account')->user()->load('getPlayers.getRank')->getPlayers;
        foreach($loggedInAccountPlayers as $accountPlayer)
        {
            $account_player_rank = $accountPlayer->getRank;
            if($account_player_rank)
            {
                foreach($rank_list as $rank_in_guild)
                {
                    if($rank_in_guild->id == $account_player_rank->id)
                    {
                        if($account_player_rank->level > 1) {
                            $guild_vice = true;
                            $level_in_guild = $account_player_rank->level;
                        }
                        if($guild->getOwner->id == $accountPlayer->id) {
                            $guild_vice = true;
                            $guild_leader = true;
                        }
                    }
                }
            }
        }

        if(!$guild_vice) {
            return redirect('/community/guilds')->withErrors('You are not a leader nor a vice leader in guild '.htmlspecialchars($guild->name).'.');
        }

        $variables = ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'rank_list' => $rank_list, 'guild_leader' => $guild_leader, 'level_in_guild' => $level_in_guild, 'guild' => $guild];
        if(!empty($sessionVariables)) {
            foreach($sessionVariables as $variable => $value) {
                $variables[$variable] = $value;
            }
        }

        return view('community.guilds.changerank', $variables);
    }

    public function updateRank(Request $request) {
        $id = $request->id;
        $request['name'] = ucwords(strtolower(trim($request->name)));
        $validator = Validator::make(['id' => $id, 'name' => $request->name, 'rankid' => $request->rankid], [
            'id' => ['required', 'numeric'],
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'max:25'],
            'rankid' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.',
            'name.required' => 'Invalid character name.',
            'name.regex' => 'Invalid character name.',
            'name.max' => 'Character name must be at most :max characters.',
            'rankid.required' => 'Invalid rank.',
            'rankid.numeric' => 'Invalid rank.',
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->with('getOwner', 'getRanks.getPlayers')->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }
    
        $rank = GuildRank::where('id', $request->rankid)->first();
        if(!$rank) {
            return back()->withErrors('Rank with this ID doesn\'t exist.');
        }

        $rank_list = $guild->getRanks()->orderBy('level', 'DESC')->get();
        if(!in_array($rank->id, $rank_list->pluck('id')->toArray())) {
            return back()->withErrors('This rank isn\'t in the guild.');
        }

        $guild_leader = false;
        $guild_vice = false;
        $level_in_guild = null;
        $loggedInAccountPlayers = auth()->guard('account')->user()->load('getPlayers.getRank')->getPlayers;
        foreach($loggedInAccountPlayers as $accountPlayer)
        {
            $account_player_rank = $accountPlayer->getRank;
            if($account_player_rank)
            {
                foreach($rank_list as $rank_in_guild)
                {
                    if($rank_in_guild->id == $account_player_rank->id)
                    {
                        if($account_player_rank->level > 1) {
                            $guild_vice = true;
                            $level_in_guild = $account_player_rank->level;
                        }
                        if($guild->getOwner->id == $accountPlayer->id) {
                            $guild_vice = true;
                            $guild_leader = true;
                        }
                    }
                }
            }
        }

        if(!$guild_vice) {
            return redirect('/community/guilds')->withErrors('You are not a leader nor a vice leader in guild '.htmlspecialchars($guild->name).'.');
        }

        if($level_in_guild <= $rank->level && !$guild_leader) {
            return back()->withErrors('You can\'t set ranks with equal or higher level than yours.');
        }

        $player = Player::where('name', $request->name)->first();
        if(!$player) {
            return back()->withErrors('Player with name '.htmlspecialchars($request->name).'</b> doesn\'t exist.');
        }

        $player_rank = $player->getRank;
        if(!$player_rank || $guild->name != $player_rank->getGuild->name) {
            return back()->withErrors('This player isn\'t in the guild.');
        }

        if($player_rank->level > $level_in_guild && !$guild_leader) {
            return back()->withErrors('This player has higher rank in guild than you. You can\'t change their rank.');
        }

        if($player_rank->level == $rank->level) {
            return back()->withErrors('This player is already a '.($rank->name).'.');
        }

        $player->rank_id = $rank->id;
        $player->save();

        $request->session()->put('variables', encrypt(['action' => 'rank_changed', 'player_name' => $player->name, 'rank_name' => $rank->name]));

        return redirect('/community/guilds/changerank/'.$guild->id);
    }

    public function cancelInvite($id, $name) {
        $name = ucwords(strtolower(trim($name)));
        $validator = Validator::make(['id' => $id, 'name' => $name], [
            'id' => ['required', 'numeric'],
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'max:25']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.',
            'name.required' => 'Invalid character name.',
            'name.regex' => 'Invalid character name.',
            'name.max' => 'Character name must be at most :max characters.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->with('getOwner', 'getRanks.getPlayers')->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $rank_list = $guild->getRanks()->orderBy('level', 'DESC')->get();
        $guild_vice = false;
        $loggedInAccountPlayers = auth()->guard('account')->user()->load('getPlayers.getRank')->getPlayers;
        foreach($loggedInAccountPlayers as $accountPlayer)
        {
            $account_player_rank = $accountPlayer->getRank;
            if($account_player_rank)
            {
                foreach($rank_list as $rank_in_guild)
                {
                    if($rank_in_guild->id == $account_player_rank->id)
                    {
                        if($account_player_rank->level > 1 || $guild->getOwner->id == $accountPlayer->id) {
                            $guild_vice = true;
                        }
                    }
                }
            }
        }

        if(!$guild_vice) {
            $validator->errors()->add('', 'You are not a leader nor a vice leader in guild '.htmlspecialchars($guild->name).'.');
            return view('community.guilds.cancelinvite', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        $player = Player::where('name', $name)->first();
        if(!$player) {
            $validator->errors()->add('', 'Player with name '.htmlspecialchars($name).'</b> doesn\'t exist.');
            return view('community.guilds.cancelinvite', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

		$invited_list = $guild->getInvites;
		if(count($invited_list) > 0)
		{
            if(!in_array($player->id, $invited_list->pluck('player_id')->toArray())) {
                $validator->errors()->add('', '<b>'.htmlspecialchars($player->name).'</b> isn\'t invited to the guild.');
                return view('community.guilds.cancelinvite', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
            }
		} else {
            $validator->errors()->add('', 'No one is invited to the guild.');
            return view('community.guilds.cancelinvite', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        return view('community.guilds.cancelinvite', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'name' => $player->name]);
    }

    public function deleteInvite(Request $request) {
        $id = $request->id;
        $name = ucwords(strtolower(trim($request->name)));
        $validator = Validator::make(['id' => $id, 'name' => $name], [
            'id' => ['required', 'numeric'],
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'max:25']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.',
            'name.required' => 'Invalid character name.',
            'name.regex' => 'Invalid character name.',
            'name.max' => 'Character name must be at most :max characters.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->with('getOwner', 'getRanks.getPlayers')->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $rank_list = $guild->getRanks()->orderBy('level', 'DESC')->get();
        $guild_vice = false;
        $loggedInAccountPlayers = auth()->guard('account')->user()->load('getPlayers.getRank')->getPlayers;
        foreach($loggedInAccountPlayers as $accountPlayer)
        {
            $account_player_rank = $accountPlayer->getRank;
            if($account_player_rank)
            {
                foreach($rank_list as $rank_in_guild)
                {
                    if($rank_in_guild->id == $account_player_rank->id)
                    {
                        if($account_player_rank->level > 1 || $guild->getOwner->id == $accountPlayer->id) {
                            $guild_vice = true;
                        }
                    }
                }
            }
        }

        if(!$guild_vice) {
            return back()->withErrors('You are not a leader nor a vice leader in guild '.htmlspecialchars($guild->name).'.');
        }

        $player = Player::where('name', $name)->first();
        if(!$player) {
            return back()->withErrors('Player with name '.htmlspecialchars($name).'</b> doesn\'t exist.');
        }

		$invited_list = $guild->getInvites;
		if(count($invited_list) > 0)
		{
            if(!in_array($player->id, $invited_list->pluck('player_id')->toArray())) {
                return back()->withErrors('<b>'.htmlspecialchars($player->name).'</b> isn\'t invited to the guild.');
            }
		} else {
            return back()->withErrors('No one is invited to your guild.');
        }
        
        DB::table('guild_invites')->where('player_id', $player->id)->where('guild_id', $guild->id)->delete();

        return view('community.guilds.cancelinvite', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'action' => 'invite_deleted', 'guild_id' => $guild->id, 'name' => $player->name]);
    }

    public function invite($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->with('getOwner', 'getRanks.getPlayers')->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $rank_list = $guild->getRanks()->orderBy('level', 'DESC')->get();
        $guild_vice = false;
        $loggedInAccountPlayers = auth()->guard('account')->user()->load('getPlayers.getRank')->getPlayers;
        foreach($loggedInAccountPlayers as $accountPlayer)
        {
            $account_player_rank = $accountPlayer->getRank;
            if($account_player_rank)
            {
                foreach($rank_list as $rank_in_guild)
                {
                    if($rank_in_guild->id == $account_player_rank->id)
                    {
                        if($account_player_rank->level > 1 || $guild->getOwner->id == $accountPlayer->id) {
                            $guild_vice = true;
                        }
                    }
                }
            }
        }

        if(!$guild_vice) {
            $validator->errors()->add('', 'You are not a leader nor a vice leader in guild '.htmlspecialchars($guild->name).'.');
            return view('community.guilds.invite', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        return view('community.guilds.invite', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id]);
    }

    public function storeInvite(Request $request) {
        $id = $request->id;
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric'],
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->with('getOwner', 'getRanks.getPlayers')->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $rank_list = $guild->getRanks()->orderBy('level', 'DESC')->get();
        $guild_vice = false;
        $loggedInAccountPlayers = auth()->guard('account')->user()->load('getPlayers.getRank')->getPlayers;
        foreach($loggedInAccountPlayers as $accountPlayer)
        {
            $account_player_rank = $accountPlayer->getRank;
            if($account_player_rank)
            {
                foreach($rank_list as $rank_in_guild)
                {
                    if($rank_in_guild->id == $account_player_rank->id)
                    {
                        if($account_player_rank->level > 1 || $guild->getOwner->id == $accountPlayer->id) {
                            $guild_vice = true;
                        }
                    }
                }
            }
        }

        if(!$guild_vice) {
            return back()->withErrors('You are not a leader nor a vice leader in guild '.htmlspecialchars($guild->name).'.');
        }

        $name = ucwords(strtolower(trim($request->name)));
        if(empty($name)) {
            return back()->with('error_same_page', "Enter a charracter name.");
        }

        $tempName = strspn("$name", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM ");
        if($tempName != strlen($name)) {
            return back()->with('error_same_page', "Invalid charracter name.")->withInput();
        }

        if(strlen($name) > 25) {
            return back()->with('error_same_page', "Character name must be at most 25 characters.")->withInput();
        }

        $player = Player::where('name', $name)->first();
        if(!$player) {
            return back()->with('error_same_page', 'Player with name '.htmlspecialchars($name).'</b> doesn\'t exist.')->withInput();
        }

		if(!empty($player->rank_id)) {
            return back()->with('error_same_page', 'Player with name <b>'.htmlspecialchars($name).'</b> is already in a guild.')->withInput();
        }

        $invited_list = $guild->getInvites;
        if(count($invited_list) > 0) {
            if(in_array($player->id, $invited_list->pluck('player_id')->toArray())) {
                return back()->with('error_same_page', '<b>'.htmlspecialchars($player->name).'</b> is already invited to the guild.')->withInput();
            }
        }

        $guildInvite = new GuildInvite;
        $guildInvite->player_id = $player->id;
        $guildInvite->guild_id = $guild->id;
        $guildInvite->save();

        return view('community.guilds.invite', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'action' => 'invite_saved', 'guild_id' => $guild->id, 'name' => $player->name]);
    }

    public function accept($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $account_invited = false;
        $loggedInAccountPlayers = auth()->guard('account')->user()->getPlayers;
        $invited_list = $guild->getInvites;
        $list_of_invited_players = [];
        if(count($invited_list) > 0)
        {
            foreach($invited_list as $invited)
            {
                foreach($loggedInAccountPlayers as $player_from_acc)
                {
                    if($invited->player_id == $player_from_acc->id)
                    {
                        $account_invited = true;
                        $list_of_invited_players[] = $player_from_acc->name;
                    }
                }
            }
        }

		if(!$account_invited)
		{
            $validator->errors()->add('', 'None of the characters in your account is invited to guild <b>'.htmlspecialchars($guild->name).'</b>.');
            return view('community.guilds.accept', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
		}

        return view('community.guilds.accept', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'list_of_invited_players' => $list_of_invited_players]);
    }

    public function joinGuild(Request $request) {
        $id = $request->id;
        $name = ucwords(strtolower(trim($request->name)));
        $validator = Validator::make(['id' => $id, 'name' => $name], [
            'id' => ['required', 'numeric'],
            'name' => ['regex:/^[\pL\s]+$/u', 'max:25']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.',
            'name.regex' => 'Invalid character name.',
            'name.max' => 'Character name must be at most :max characters.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        if(empty($name)) {
            return back()->with('error_same_page', "Select a charracter.");
        }

        $player = Player::where('name', $name)->first();
        if(!$player) {
            return back()->withErrors('Player with name '.htmlspecialchars($name).'</b> doesn\'t exist.');
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($loggedInAccount->id != $player->account_id) {
            return back()->withErrors('Player with name '.htmlspecialchars($player->name).'</b> is not in your account.');
        }

        if(!empty($player->rank_id)) {
            return back()->withErrors('Character with name <b>'.htmlspecialchars($player->name).'</b> is already in a guild.');
        }

        $invited_list = $guild->getInvites;
        if(count($invited_list) > 0) {
            if(!in_array($player->id, $invited_list->pluck('player_id')->toArray())) {
                return back()->withErrors('Character '.htmlspecialchars($player->name).' isn\'t invited to guild <b>'.htmlspecialchars($guild->name).'</b>.');
            }
        }

        $lowestGuildRank = $guild->getRanks()->orderBy('level', 'ASC')->first();
        $player->rank_id = $lowestGuildRank->id;
        $player->save();
        DB::table('guild_invites')->where('player_id', $player->id)->delete();
        
        return view('community.guilds.accept', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'action' => 'joined_guild', 'guild_id' => $guild->id, 'guild_name' =>  $guild->name, 'player_name' => $player->name]);
    }

    public function kick($id, $name) {
        $name = ucwords(strtolower(trim($name)));
        $validator = Validator::make(['id' => $id, 'name' => $name], [
            'id' => ['required', 'numeric'],
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'max:25']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.',
            'name.required' => 'Invalid character name.',
            'name.regex' => 'Invalid character name.',
            'name.max' => 'Character name must be at most :max characters.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->with('getOwner', 'getRanks.getPlayers')->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $rank_list = $guild->getRanks()->orderBy('level', 'DESC')->get();
        $loggedInAccountPlayers = auth()->guard('account')->user()->load('getPlayers.getRank')->getPlayers;
        $guild_leader = false;
        $level_in_guild = null;
        foreach($loggedInAccountPlayers as $accountPlayer)
        {
            $account_player_rank = $accountPlayer->getRank;
            if($account_player_rank)
            {
                foreach($rank_list as $rank_in_guild)
                {
                    if($rank_in_guild->id == $account_player_rank->id)
                    {
                        if($account_player_rank->level > 1) {
                            $level_in_guild = $account_player_rank->level;
                        }
                        if($guild->getOwner->id == $accountPlayer->id) {
                            $guild_leader = true;
                        }
                    }
                }
            }
        }

        if(!$guild_leader && $level_in_guild < 3) {
            $validator->errors()->add('', 'You are not a leader of guild <b>'.htmlspecialchars($guild->name).'</b>. You can\'t kick players.');
            return view('community.guilds.kick', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
		}

        $player = Player::where('name', $name)->first();
        if(!$player) {
            $validator->errors()->add('', 'Player with name '.htmlspecialchars($name).'</b> doesn\'t exist.');
            return view('community.guilds.kick', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        $player_rank = $player->getRank;
        if(!$player_rank || $guild->name != $player_rank->getGuild->name) {
            $validator->errors()->add('', 'Player with name '.htmlspecialchars($name).'</b> isn\'t in the guild.');
            return view('community.guilds.kick', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

		if($player_rank->level >= $level_in_guild && !$guild_leader) {
            $validator->errors()->add('', 'You can\'t kick players with ranks equal or higher level than yours.');
            return view('community.guilds.kick', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
		}

        if($guild->getOwner->name == $player->name) {
            $validator->errors()->add('', 'It\'s not possible to kick the guild owner.');
            return view('community.guilds.kick', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        return view('community.guilds.kick', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'player_name' => $player->name]);    
    }

    public function disjoin(Request $request) {
        $id = $request->id;
        $name = ucwords(strtolower(trim($request->name)));
        $validator = Validator::make(['id' => $id, 'name' => $name], [
            'id' => ['required', 'numeric'],
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'max:25']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.',
            'name.required' => 'Invalid character name.',
            'name.regex' => 'Invalid character name.',
            'name.max' => 'Character name must be at most :max characters.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->with('getOwner', 'getRanks.getPlayers')->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $rank_list = $guild->getRanks()->orderBy('level', 'DESC')->get();
        $loggedInAccountPlayers = auth()->guard('account')->user()->load('getPlayers.getRank')->getPlayers;
        $guild_leader = false;
        $level_in_guild = null;
        foreach($loggedInAccountPlayers as $accountPlayer)
        {
            $account_player_rank = $accountPlayer->getRank;
            if($account_player_rank)
            {
                foreach($rank_list as $rank_in_guild)
                {
                    if($rank_in_guild->id == $account_player_rank->id)
                    {
                        if($account_player_rank->level > 1) {
                            $level_in_guild = $account_player_rank->level;
                        }
                        if($guild->getOwner->id == $accountPlayer->id) {
                            $guild_leader = true;
                        }
                    }
                }
            }
        }

        if(!$guild_leader && $level_in_guild < 3) {
            return back()->withErrors('You are not a leader of guild <b>'.htmlspecialchars($guild->name).'</b>. You can\'t kick players.');
		}
        
        $player = Player::where('name', $name)->first();
        if(!$player) {
            return back()->withErrors('Player with name '.htmlspecialchars($name).'</b> doesn\'t exist.');
        }

        $player_rank = $player->getRank;
        if(!$player_rank || $guild->name != $player_rank->getGuild->name) {
            return back()->withErrors('Player with name '.htmlspecialchars($name).'</b> isn\'t in the guild.');
        }

		if($player_rank->level >= $level_in_guild && !$guild_leader) {
            return back()->withErrors('You can\'t kick players with ranks equal or higher level than yours.');
		}

        if($guild->getOwner->name == $player->name) {
            return back()->withErrors('It\'s not possible to kick the guild owner.');
        }

        $player->rank_id = 0;
        $player->save();

        return view('community.guilds.kick', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'action' => 'disjoined_guild', 'guild_id' => $guild->id, 'player_name' => $player->name]);
    }
    
    public function leave($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $guild_owner = $guild->getOwner;
        $loggedInAccount = auth()->guard('account')->user();
        $loggedInAccountPlayers = $loggedInAccount->load('getPlayers.getRank', 'getPlayers.getRank.getGuild')->getPlayers;
        $players_in_guild = [];
        foreach($loggedInAccountPlayers as $accountPlayer)
        {
            $account_player_rank = $accountPlayer->getRank;
            if($account_player_rank)
            {
                if($account_player_rank->getGuild->id == $guild->id) {
                    if($guild_owner->id != $accountPlayer->id) {
                        $players_in_guild[] = $accountPlayer->name;
                    }
                }
            }
        }

		if(count($players_in_guild) == 0)
		{
            $validator->errors()->add('', 'None of the characters in your account is in guild <b>'.htmlspecialchars($guild->name).'</b>'.($guild_owner->account_id == $loggedInAccount->id ? ' or you are the owner of it' : '').'.');
            return view('community.guilds.leave', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
		}

        return view('community.guilds.leave', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'players_in_guild' => $players_in_guild]);
    }

    public function leaveGuild(Request $request) {
        $id = $request->id;
        $name = ucwords(strtolower(trim($request->name)));
        $validator = Validator::make(['id' => $id, 'name' => $name], [
            'id' => ['required', 'numeric'],
            'name' => ['regex:/^[\pL\s]+$/u', 'max:25']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.',
            'name.regex' => 'Invalid character name.',
            'name.max' => 'Character name must be at most :max characters.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        if(empty($name)) {
            return back()->with('error_same_page', "Select a charracter.");
        }

        $player = Player::where('name', $name)->first();
        if(!$player) {
            return back()->withErrors('Player with name '.htmlspecialchars($name).'</b> doesn\'t exist.');
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($loggedInAccount->id != $player->account_id) {
            return back()->withErrors('Player with name '.htmlspecialchars($player->name).'</b> is not in your account.');
        }
        
        $playerRank = $player->getRank;
        if(!$playerRank) {
            return back()->withErrors('Character <b>'.htmlspecialchars($player->name).'</b> isn\'t in any guild.');
        }

        if($playerRank->getGuild->id != $guild->id) {
            return back()->withErrors('Character <b>'.htmlspecialchars($player->name).'</b> isn\'t in guild <b>'.htmlspecialchars($guild->name).'</b>.');
        }
    
        if($guild->getOwner->id == $player->id) {
            return back()->withErrors('You can\'t leave the guild as you\'re the owner of it.');
        }

        $player->rank_id = 0;
        $player->save();

        return view('community.guilds.leave', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'action' => 'left_guild', 'guild_id' => $guild->id, 'guild_name' => $guild->name, 'player_name' => $player->name]);
    }

    public function deleteByAdmin($id) {
        $loggedInAccount = auth()->guard('account')->user();
        if($loggedInAccount->page_access < config('custom.access_admin_panel')) {
            return redirect('/community/guilds')->withErrors('You are not an admin.');
        }

        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        return view('community.guilds.deletebyadmin', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'guild_name' => $guild->name]);
    }

    public function deleteGuildByAdmin(Request $request) {
        $loggedInAccount = auth()->guard('account')->user();
        if($loggedInAccount->page_access < config('custom.access_admin_panel')) {
            return redirect('/community/guilds')->withErrors('You are not an admin.');
        }

        $id = $request->id;
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $guildPlayers = $guild->getPlayers;
        if(count($guild->getPlayers) > 0) {
            foreach($guildPlayers as $player) {
                $player->rank_id = 0;
                $player->save();
            }
        }

        // Deleting a guild also deletes all invites and ranks (on Delete Cascade)
        /*
        DB::table('guild_invites')->where('guild_id', $guild->id)->delete();
        $guild->getRanks()->delete();
        */
        $guild->delete();

        $oldLogo = $guild->logo_gfx_name;
        if(!empty($oldLogo)) {
            $oldLogoPath = 'guilds/'.$oldLogo;
            if(Storage::disk('public')->exists($oldLogoPath)) {
                Storage::disk('public')->delete($oldLogoPath);
            }
        }

        return view('community.guilds.deletebyadmin', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'action' => 'guild_deleted', 'guild_id' => $guild->id]);
    }

    public function changeNick(Request $request) {
        $loggedInAccount = auth()->guard('account')->user();
        if(count($loggedInAccount->getPlayers) == 0) {
            return redirect('/community/guilds')->withErrors('You don\'t have characters in your account.');
        }

        $name = ucwords(strtolower(trim($request->name)));
        $validator = Validator::make(['name' => $name], [
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'max:25']
        ], [
            'name.required' => 'Invalid character name.',
            'name.regex' => 'Invalid character name.',
            'name.max' => 'Character name must be at most :max characters.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $player = Player::where('name', $name)->first();
        if(!$player) {
            return redirect('/community/guilds')->withErrors('Character <b>'.$request->name.'</b> doesn\'t exist.');
        }

        if($player->account_id != $loggedInAccount->id) {
            return redirect('/community/guilds')->withErrors('This character is not in your account.');
        }

        $playerRank = $player->getRank;
        if(!$playerRank) {
            return redirect('/community/guilds')->withErrors('Character <b>'.$player->name.'</b> is not in a guild.');
        }

        $guild_id = $playerRank->getGuild->id;
        $nick = (string) trim($request->nick);
        $length = strlen($nick);
        if($length > 40) {
            return redirect('/community/guilds/'.$guild_id)->with('message_same_page', '<font color="red" size="2"><b>Error occured:</b> Guild nick must be at most 40 characters.</font>');
        }

        $player->guildnick = $nick;
        $player->save();

        return redirect('/community/guilds/'.$guild_id)->with('message_same_page', '<font color="green" size="2"><b>Information:</b> Guild nick of player <b>'.htmlspecialchars($player->name).'</b> was '.($length == 0 ? 'erased' : 'changed to <b>'.htmlentities($nick).'</b>').'.</font>');
    }

    public function manage($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($guild->getOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild' => $guild]); 
    }

    public function changeLogo($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($guild->getOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        return view('community.guilds.changelogo', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild' => $guild]);
    }

    public function saveLogo(Request $request) {
        $id = $request->id;
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($guild->getOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        /*
        // This is an alternate for checking the uploaded file
        $request->validate([
            'newlogo' => 'required|image|mimes:jpeg,png,jpg,gif|max:51200',
        ]);
        */

        if(!$request->hasFile('newlogo')) {
            return back()->withErrors('Upload an image that is at most '.config('custom.guild_image_size_kb').' KB.');
        }

        $file = $request->file('newlogo');
        $uploadErrors = [];
        switch($file->getError()) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $uploadErrors[] = 'Image is too large.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $uploadErrors[] = 'Image was only partially uploaded.';
                break;
            case UPLOAD_ERR_NO_FILE:
                $uploadErrors[] = 'No image was uploaded.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $uploadErrors[] = 'Upload folder not found.';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $uploadErrors[] = 'Unable to write uploaded file.';
                break;
            case UPLOAD_ERR_EXTENSION:
                $uploadErrors[] = 'Upload failed due to extension.';
                break;
            default:
                $uploadErrors[] = 'Unknown error.';
        }

        if(!empty($uploadErrors)) {
            return back()->withErrors($uploadErrors);
        }

        $allowedMimeTypes = config('custom.guild_image_allowed_types');
        if(!array_key_exists($file->getMimeType(), $allowedMimeTypes)) {
            $foundExtensions = [];
            foreach(config('custom.guild_image_allowed_types') as $extension) {
                if(!in_array($extension, $foundExtensions)) {
                    $foundExtensions[] = $extension;
                }
            }
            $foundExtensionsCount = count($foundExtensions);
            $allowedExtensions = '';
            foreach($foundExtensions as $index => $extension) {
                $allowedExtensions .= ($index+1 == $foundExtensionsCount ? ' and ' : ($index+1 > 1 ? ', ' : '')).'<b>'.$extension.'</b>';
            }
            return back()->withErrors('The file type isn\'t a valid image. Only '.$allowedExtensions.' are allowed.');
        }

        if(!getimagesize($file->getPathname())) {
            return back()->withErrors('Uploaded image is not valid.');
        } 

        $maxImageSizeBytes = config('custom.guild_image_size_kb') * 1024;
        if($file->getSize() > $maxImageSizeBytes) {
            return back()->withErrors('Uploaded image is too big. Size: '.round($file->getSize()/1024).' KB, Max. size: '.($maxImageSizeBytes/1024).' KB.');
        }

        $oldLogo = $guild->logo_gfx_name;

        /*
        // This is an alternate to delete the old logo before creating the new one. However, 
        // Laravel's Storage facade is generally preferred because it provides a more consistent and abstracted way to handle file storage across different filesystems
        if(!empty($oldLogo)) {
            $oldLogoPath = public_path('storage/guilds/'.$oldLogo);
            if(file_exists($oldLogoPath)) {
                unlink($oldLogoPath);
            }
        }
        */
        
        if(!empty($oldLogo)) {
            $oldLogoPath = 'guilds/'.$oldLogo;
            if(Storage::disk('public')->exists($oldLogoPath)) {
                Storage::disk('public')->delete($oldLogoPath);
            }
        }

        /*
        // This is an alternate to save the logo without the folder name since store method returns the folder along with the hashed image name
        $newLogo = $file->hashName();
        $file->storeAs('guilds', $newLogo, 'public');
        */

        $newLogo = $file->store('guilds', 'public');
        $newLogo = basename($newLogo);

		$guild->logo_gfx_name = $newLogo;
		$guild->save();

        return redirect('/community/guilds/manage/'.$guild->id)->with('message_same_page', '<font color="green" size="2"><b>Information:</b> Guild logo has been changed.</font>');
    }

    public function addRank(Request $request) {
        $id = $request->id;
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($guild->getOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        if(count($guild->getRanks) >= config('custom.guild_max_ranks')) {
            $validator->errors()->add('', 'You can\'t add more than '.config('custom.guild_max_ranks').' ranks.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors(), 'returnToManage' => true]);
        }

        $rankName = ucwords(trim($request->rank_name));
        $validator = Validator::make(['rank_name' => $rankName], [
            'rank_name' => ['required', 'regex:/^[a-zA-Z0-9\- \[\]]+$/', 'min:3', 'max:60']
        ], [
            'rank_name.required' => 'Type a rank name.',
            'rank_name.regex' => 'Invalid rank name.',
            'rank_name.min' => 'Rank name must be at least :min characters.',
            'rank_name.max' => 'Rank name must be at most :max characters.'
        ]);

        if($validator->fails()) {
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors(), 'returnToManage' => true]);
        }

        $newRank = new GuildRank;
        $newRank->guild_id = $guild->id;
        $newRank->name = $rankName;
        $newRank->level = 1;
        $newRank->save();

        return redirect('/community/guilds/manage/'.$guild->id)->with('message_same_page', '<font color="green" size="2"><b>Information:</b> Guild rank <b>'.$rankName.'</b> has been added.</font>');
    }

    public function deleteRank($id, $rank) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($guild->getOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        $validator = Validator::make(['rank' => $rank], [
            'rank' => ['required', 'numeric']
        ], [
            'rank.required' => 'Invalid rank id.',
            'rank.numeric' => 'Invalid rank id.'
        ]);

        if($validator->fails()) {
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors(), 'returnToManage' => true]);
        }

        $rankToDelete = GuildRank::where('id', $rank)->first();
        if(!$rankToDelete) {
            $validator->errors()->add('', 'Rank with ID '.$rank.' doesn\'t exist.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors(), 'returnToManage' => true]);
        }
        
        if($rankToDelete->getGuild->id != $guild->id)
        {
            $validator->errors()->add('', 'Rank with ID '.$rank.' isn\'t in the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors(), 'returnToManage' => true]);
        }

        $guildRanks = $guild->getRanks;
        if(count($guildRanks) < 2) {
            $validator->errors()->add('', 'The guild has only one rank and it can\'t be deleted.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors(), 'returnToManage' => true]);
        }

        $playersWithRankToDelete = $rankToDelete->getPlayers;
        $playersWithRankToDeleteCount = count($playersWithRankToDelete);
        if($playersWithRankToDeleteCount > 0)
        {
            $newRank = null;
            foreach($guildRanks as $guildRank) {
                if($guildRank->id != $rankToDelete->id) {
                    if($guildRank->level <= $rankToDelete->level) {
                        $newRank = $guildRank;
                    }
                }
            }

            if(!isset($newRank))
            {
                $newRank = new GuildRank;
                $newRank->guild_id = $guild->id;
                $newRank->name = 'New Rank level '.$rankToDelete->level;
                $newRank->level = $rankToDelete->level;
                $newRank->save();
            }

            foreach($playersWithRankToDelete as $playerWithRankToDelete)
            {
                $playerWithRankToDelete->rank_id = $newRank->id;
                $playerWithRankToDelete->save();
            }
        }

        $rankToDelete->delete();

        return redirect('/community/guilds/manage/'.$guild->id)->with('message_same_page', '<font color="green" size="2"><b>Information:</b> Rank <b>'.htmlspecialchars($rankToDelete->name).'</b> has been deleted'.($playersWithRankToDeleteCount > 0 ? ' and players with that rank now have rank <b>'.$newRank->name.'</b>' : '').'.</font>');
    }

    public function saveRanks(Request $request) {
        $id = $request->id;
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        if(!($request->session()->has('save_ranks') && $request->session()->get('save_ranks'))) {
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild' => $guild]); 
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($guild->getOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        $errors = [];
        foreach($guild->getRanks as $rank)
		{
            $rank_id = $rank->id;
            $name = (string) $request[$rank_id.'_name'];
            $level = (int) $request[$rank_id.'_level'];
            if(Functions::checkRankName($name)) {
                $rank->name = $name;
            } else {
                $errors[] = 'Invalid rank name. Please use only a-Z, 0-9 and spaces. Rank ID <b>'.$rank_id.'</b>.';
            }
            if($level > 0 && $level < 4) {
                $rank->level = $level;
            } else {
                $errors[] = 'Invalid rank level. Rank ID <b>'.$rank_id.'</b>.';
            }
            $rank->save();
        }

        if(!empty($errors))
        {
            foreach($errors as $error) {
                $validator->errors()->add('', $error);
            }
            return view('community.guilds.saveranks', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        $request->session()->forget('save_ranks');

        return view('community.guilds.saveranks', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id]);
    }
    
    public function delete($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }
        
        $loggedInAccount = auth()->guard('account')->user();
        if($guild->getOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        return view('community.guilds.delete', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'guild_name' => $guild->name]);
    }

    public function deleteGuild(Request $request) {
        $id = $request->id;
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($guild->getOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        $guildPlayers = $guild->getPlayers;
        if(count($guild->getPlayers) > 0) {
            foreach($guildPlayers as $player) {
                $player->rank_id = 0;
                $player->save();
            }
        }

        // Deleting a guild also deletes all invites and ranks (on Delete Cascade)
        /*
        DB::table('guild_invites')->where('guild_id', $guild->id)->delete();
        $guild->getRanks()->delete();
        */
        $guild->delete();

        $oldLogo = $guild->logo_gfx_name;
        if(!empty($oldLogo)) {
            $oldLogoPath = 'guilds/'.$oldLogo;
            if(Storage::disk('public')->exists($oldLogoPath)) {
                Storage::disk('public')->delete($oldLogoPath);
            }
        }
        
        return view('community.guilds.delete', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'action' => 'guild_deleted', 'guild_id' => $guild->id]); 
    }

    public function passLeadership($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }
        
        $guildOwner = $guild->getOwner;
        $loggedInAccount = auth()->guard('account')->user();
        if($guildOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        $guildPlayers = $guild->getPlayers()->where('players.id', '!=', $guildOwner->id)->get();
        if(count($guildPlayers) == 0) {
            $validator->errors()->add('', 'There are no players in the guild except for the owner.');
            return view('community.guilds.passleadership', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }
        
        $namesOfGuildPlayers = $guildPlayers->pluck('name')->toArray();
        return view('community.guilds.passleadership', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'namesOfGuildPlayers' => $namesOfGuildPlayers]);
    }

    public function leadershipPassed(Request $request) {
        $id = $request->id;
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $guildOwner = $guild->getOwner;
        $loggedInAccount = auth()->guard('account')->user();
        if($guildOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        $name = ucwords(strtolower(trim($request->name)));
        $validator = Validator::make(['name' => $name], [
            'name' => ['required', 'regex:/^[\pL\s]+$/u', 'max:25']
        ], [
            'name.required' => 'Select a character.',
            'name.regex' => 'Invalid character name.',
            'name.max' => 'Character name must be at most :max characters.'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $player = Player::where('name', $name)->first();
        if(!$player) {
            return back()->withErrors('Character <b>'.$name.'</b> doesn\'t exist.');
        }

        if($player->id == $guildOwner->id) {
            return back()->withErrors('Leadership can\'t be passed to the current owner.');
        }

        $playerRank = $player->getRank;
        if(!$playerRank || $playerRank->getGuild->id != $guild->id) {
            return back()->withErrors('Player with name <b>'.htmlspecialchars($player->name).'</b> is not in the guild.');
        }

        $guild->ownerid = $player->id;
        $guild->save();
       
        $newOwnerFromAccount = $player->account_id == $loggedInAccount->id ? true : false;
        return view('community.guilds.passleadership', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'action' => 'leadership_passed', 'guild_id' => $guild->id, 'guild_name' => $guild->name, 'player_name' => $player->name, 'newOwnerFromAccount' => $newOwnerFromAccount]);
    }

    public function changeMotd($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($guild->getOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        return view('community.guilds.changemotd', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild' => $guild]);
    }

    public function saveMotd(Request $request) {
        $id = $request->id;
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($guild->getOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        $text = (string) htmlspecialchars(stripslashes(substr(trim($request->motd), 0, config('custom.guild_motd_chars_limit'))));

		$guild->motd = $text;
		$guild->save();

        return redirect('/community/guilds/manage/'.$guild->id)->with('message_same_page', '<font color="green" size="2"><b>Information:</b> Guild MOTD has been updated.</font>');
    }
    
    public function changeDescription($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($guild->getOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        return view('community.guilds.changedescription', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild' => $guild]);
    }

    public function saveDescription(Request $request) {
        $id = $request->id;
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => 'Invalid guild id.',
            'id.numeric' => 'Invalid guild id.'
        ]);

        if($validator->fails()) {
            return redirect('/community/guilds')->withErrors($validator);
        }

        $guild = Guild::where('id', $id)->first();
        if(!$guild) {
            return redirect('/community/guilds')->withErrors('Guild with ID <b>'.$id.'</b> doesn\'t exist.');
        }

        $loggedInAccount = auth()->guard('account')->user();
        if($guild->getOwner->account_id != $loggedInAccount->id) {
            $validator->errors()->add('', 'You are not the owner of the guild.');
            return view('community.guilds.manage', ['pageTitle' => 'Guilds', 'subtopic' => 'guilds', 'guild_id' => $guild->id, 'errors' => $validator->errors()]);
        }

        $text = (string) htmlspecialchars(stripslashes(substr(trim($request->description), 0, config('custom.guild_description_chars_limit'))));
        if(empty($text)) {
            return back()->withErrors('Guild descreption can\'t be empty.');
        }

		$guild->description = $text;
		$guild->save();

        return redirect('/community/guilds/manage/'.$guild->id)->with('message_same_page', '<font color="green" size="2"><b>Information:</b> Guild description has been saved.</font>');
    }
}