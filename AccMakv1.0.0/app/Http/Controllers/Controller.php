<?php

namespace App\Http\Controllers;

use App\Classes\Visitors;
use App\Classes\PageViewsCounter;
use App\Classes\ServerStatusValues;
use Illuminate\Support\Facades\View;

abstract class Controller
{
    public function __construct()
    {
        $this->shareVariables();
    }

    private function shareVariables() {
        $storageAppPath = storage_path('app');

        $serverStatus = (new ServerStatusValues(app('server_config'), $storageAppPath.'/serverstatus.txt'))->getStatus();
  
        app()->instance('server_status', $serverStatus);

        View::share('server_status', $serverStatus);
        View::share('page_views', (new PageViewsCounter($storageAppPath.'/usercounter.txt'))->getViews());
        View::share('visitors', (new Visitors(config('custom.visitors_counter_ttl')))->getAmountVisitors());

        // Usage in view files -> $variable_Name
        // Usage in controllers and models -> app('variable_Name')
    }
}
