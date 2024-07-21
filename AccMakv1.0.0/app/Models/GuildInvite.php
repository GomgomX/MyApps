<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildInvite extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    public function getPlayer() {
        return $this->belongsTo(Player::class, 'player_id');
    }
}