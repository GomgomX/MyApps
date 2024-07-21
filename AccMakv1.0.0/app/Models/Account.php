<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

class Account extends User implements MustVerifyEmail
{
    protected $fillable = [
        'name',
        'password',
        'premdays',
        'email',
        'blocked',
        'group_id',
        'page_lastday',
        'created',
        'page_access',
        'flag'
    ];

    public function getPlayers() {
        return $this->hasMany(Player::class, 'account_id');
    }
    
    public function banned() {
        return Ban::where('type', 3)->where('value', $this->id)->first('expires');
    }
}