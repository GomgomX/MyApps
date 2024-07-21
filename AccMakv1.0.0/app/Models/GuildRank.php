<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildRank extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    public function getGuild() {
        return $this->belongsTo(Guild::class, 'guild_id');
    }
    
    public function getPlayers() {
        return $this->hasMany(Player::class, 'rank_id');
    }
}