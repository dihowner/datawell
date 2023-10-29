<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share the admin user data with all views
        view()->composer('*', function ($view) {
            $view->with('adminUser', Auth::guard('admin')->user());
        }); 

        Paginator::defaultView('pagination::simple-default');
    }
}