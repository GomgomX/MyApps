<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;

/* 
- We can use the following chained methods with routes: middleware('auth'), name('login'), response(null, 409) & ( where('wildcard', '.*'), where('wildcard', '[A-Za-z]+') or where('wildcard', '[0-9]+') )
- If we want to find a row by a spacific field instead of the primary key (which is the id field by default)  in the database through route model binding, we can simply pass the field name along with the fildcard
-> {listing:name}
- It was advised to pass the model name as a wildcard not just a variable to use route model binding functionality but that wasn't approved to be true
*/

// Show All Listings
Route::get('/', [ListingController::class, 'index']);

// Show Create Form
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

// Store Listing Data
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

// Show Manage Page
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

// Show Edit Form
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

// Update Listing
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

// Delete Listing
Route::delete('/listings/{listing}', [ListingController::class, 'destory'])->middleware('auth');

// Show Single Listing
Route::get('/listings/{listing}', [ListingController::class, 'show']);

// Show Register/Create Form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// Create New User
Route::post('/users', [UserController::class, 'store'])->middleware('guest');

// Log User Out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// Show Login Form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// Log User In
Route::post('/users/authenticate', [UserController::class, 'authenticate'])->middleware('guest');

//Route::fallback([Controller::class, 'function']); // This will be executed when no other route matches the incoming request. It has to be the last route registered
/* 
-We can place two routes with the same URL since the second one will inherent from the first one as long as they have different request method
-> Route::get('posts'), [PlayerController::class, 'threads'])->name('threads');
-> Route::post('posts'), [PlayerController::class, 'store']); // This one will inherent from the first one since they gave the same route with different request method
*/