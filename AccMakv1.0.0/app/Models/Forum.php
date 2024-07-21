<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    protected $table = "z_forum";

    public function getAccount() {
        return $this->belongsTo(Account::class, 'author_aid');
    }

    public function getPlayer() {
        return $this->belongsTo(Player::class, 'author_guid');
    }
}
