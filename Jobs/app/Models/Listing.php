<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;
    /* 
    - If we don't want to use the default table name (the "snake case", plural name of the class), we should specify it to the model:
    -> protected $table = 'Table_Name';
    */
    // We can comment the below line and ungaurd the model in AppServiceProvider.php to allow inserting to a table without checking which fields can be added
    protected $fillable = ['title', 'company', 'location', 'website', 'email', 'tags', 'desc', 'logo', 'user_id'];
    /* 
    - We can also guard some field from mass assignemt. In other wards, guarded fields can't be filled with create method
    protected $guarded = ['title', 'company']
    */


    public function scopeFilter($query, array $filters) {
        if(isset($filters['tag'])){
            $query->where('tags', 'like', '%'.$filters['tag'].'%');
        }

        if(isset($filters['search'])){
            $query->where('title', 'like', '%'.$filters['search'].'%')->orWhere('desc', 'like', '%'.$filters['search'].'%')->orWhere('tags', 'like', '%'.$filters['search'].'%');
        }
        
        if(isset($filters['order'])){
            $query->orderBy('id', 'desc');
        }
    }

    // Eloquent relationship
    /*
    - Using the property instead of the method returns something different
    - In case we want to return user's elements such as name or id we use the eloquent relationship to return the property
    -> listings->user->name
    */
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}