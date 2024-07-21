<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlayerDeath extends Model
{
    use HasFactory;

    public function scopeFilter($query, $limit = 1) {
        return $query->orderBy('date', 'desc')->take($limit);
    }

    public function getPlayer() {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function getKillers() {
        return $this->hasMany(Killer::class, 'death_id')
        ->select('killers.death_id', 'environment_killers.name AS monster_name', 'players.name AS player_name', 'players.deleted AS player_exists')
        ->leftJoin('environment_killers', 'killers.id', '=', 'environment_killers.kill_id')
        ->leftJoin('player_killers', 'killers.id', '=', 'player_killers.kill_id')
        ->leftJoin('players', 'players.id', '=', 'player_killers.player_id')
        ->orderBy('killers.final_hit', 'DESC')
        ->orderBy('killers.id', 'ASC');
    }
}