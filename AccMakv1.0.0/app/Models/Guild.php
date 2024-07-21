<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    public function getGuildLogoLink() {
        if(!empty($this->logo_gfx_name) && file_exists(public_path('storage/guilds')."/".$this->logo_gfx_name)) {
            return asset('storage/guilds').'/'.$this->logo_gfx_name;
        }

        return asset('storage/guilds').'/default_guild_logo.gif';
    }

    public function getOwner() {
        return $this->belongsTo(Player::class, 'ownerid');
    }

    public function getRanks() {
        return $this->hasMany(GuildRank::class, 'guild_id');
    }

    public function getInvites() {
        return $this->hasMany(GuildInvite::class, 'guild_id');
    }

    public function getPlayers() {
        return $this->hasManyThrough(Player::class, GuildRank::class, 'guild_id', 'rank_id', 'id', 'id');
    }
}