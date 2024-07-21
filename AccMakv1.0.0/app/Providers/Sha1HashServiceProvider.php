<?php

namespace App\Providers;

use App\Classes\Sha1Hasher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class Sha1HashServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Hash::extend('sha1', function() {
            return new Sha1Hasher();
        });
    }
}