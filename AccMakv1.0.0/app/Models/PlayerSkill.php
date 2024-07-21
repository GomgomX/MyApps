<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerSkill extends Model
{
    use HasFactory;

    public function getPlayer() {
        return $this->belongsTo(Player::class, 'player_id');
    }
}