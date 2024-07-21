<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use App\Services\UpdateProfitService;
use Illuminate\Support\ServiceProvider;
use App\Contracts\UpdateProfitInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UpdateProfitInterface::class, UpdateProfitService::class);
        // This method performes the same as the one above. However, it doesn't require an interface
        //  $this->app->singleton(UpdateProfitService::class, function() {
        //      return new UpdateProfitService;
        //  });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
