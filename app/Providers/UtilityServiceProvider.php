<?php

namespace App\Providers;

use App\Services\UtilityService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class UtilityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(UtilityService::class, function ($app) {
            return new UtilityService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(UtilityService $utilityService)
    {
        View::composer('*', function ($view) {
            $utilityService = new UtilityService();
            $view->with('UtilityService', $utilityService);
        });
    }

}