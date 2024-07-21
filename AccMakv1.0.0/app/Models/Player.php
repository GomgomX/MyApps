<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    public function getAccount() {
        return $this->belongsTo(Account::class, 'account_id');
    }
    
    public function getSkills() {
        return $this->hasMany(PlayerSkill::class, 'player_id');
    }

    public function getStorages() {
        return $this->hasMany(PlayerStorage::class, 'player_id');
    }

    public function getItems() {
        return $this->hasMany(PlayerItem::class, 'player_id');
    }

    public function getDeaths() {
        return $this->hasMany(PlayerDeath::class, 'player_id')->with('getKillers');
    }

    public function getThreads() {
        return $this->hasMany(Forum::class, 'author_guid');
    }

    public function getRank() {
        return $this->belongsTo(GuildRank::class, 'rank_id');
    }

    public function isBanned() {
        return Ban::where('type', 2)->where('value', $this->id)->first('expires');
    }

    public function scopeOrder($query, $field = "name") {
        if(in_array($field, ["nameasc", "namedesc"])) {
            if($field == "nameasc")
                $query->orderBy('name', 'asc');
            else
                $query->orderBy('name', 'desc');
        }
        
        if(in_array($field, ["levelasc", "leveldesc"])) {
            if($field == "levelasc")
                $query->orderBy('level', 'asc');
            else
                $query->orderBy('level', 'desc');
        }

        if(in_array($field, ["vocationasc", "vocationdesc"])) {
            if($field == "vocationasc")
                $query->orderBy('vocation', 'asc');
            else
                $query->orderBy('vocation', 'desc');
        }
    }

    public function scopeSort($query, array $filters) {
        $skills = array('fist' => 0, 'club' => 1, 'sword' => 2, 'axe' => 3, 'distance' => 4, 'shielding' => 5, 'fishing' => 6);
        if(!isset($filters['skill']) || ($filters['skill'] != "experience" && $filters['skill'] != "magic" && !array_key_exists($filters['skill'], $skills))) {
            $filters['skill'] = "experience";
        }

        if($filters['skill'] == "experience") {
            $query->orderBy('level', 'desc');
        } elseif($filters['skill'] == "magic") {
            $query->orderBy('maglevel', 'desc');
        } else {
            $query->select('players.*', 'player_skills.*')->join('player_skills', 'player_skills.player_id', '=', 'players.id')->where('skillid', $skills[$filters['skill']])->orderBy('value', 'desc')->orderBy('count', 'desc');
        }

        $vocs = array('sorcerer' => 1, 'druid' => 2, 'paladin' => 3, 'knight' => 4);
        if(isset($filters['vocation']) && array_key_exists($filters['vocation'], $vocs)) {
            $query->where('vocation', $vocs[$filters['vocation']]);
        }
    }
}