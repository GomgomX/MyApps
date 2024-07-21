<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Eloquent relationship
    /*
    - We can call relationship method (hasMany relationship: listings()) to return the quary itself to deal with it as an object to fetch data from the database using Eloquent
    - So we can deal with the Eloquent relationship as an object to execute methods such as create to insert rows into the database
    - But if we call the property (relationship method without parenthesis: listing) we can return a collection of entries from database
    - Collection class can return elements from database by listing them in a wrapper. In other words, if we access a relationship colletion we get a collection object back
    - One of the collection method that we can use is contains(field, value)
    -> User()->find(1)->listings->contains("user_id", $value)
    */
    public function listings() {
        return $this->hasMany(Listing::class, 'user_id');
    }

    /*
    - We can have an Eloquent relationship with more than 1 table if there an intermediate which is the user in this case
    - We can use this if the user is common and the user can have many likes on each post
    public function reciviedLikes() {
        return $this->hasManyThrough(
            Like::class,
            Post::class,
            'user_id', //Foreign key on the posts table
            'user_id', //Foreign key on the likes table
            'id', // Local Primary key on the users table
            'id' // Local Primary key on the posts table
        );
    }
    */
}
