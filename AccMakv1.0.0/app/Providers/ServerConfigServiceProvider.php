<?php

namespace App\Providers;

use App\Classes\ConfigLUA;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ServerConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $serverConfig = (new ConfigLUA(config('custom.serverPath') . 'config.lua'))->getConfig();
        View::share('server_config', $serverConfig);
        $this->app->instance('server_config', $serverConfig);

        // Usage in view files -> $variable_Name
        // Usage in controllers and models -> app('variable_Name')
    }
}