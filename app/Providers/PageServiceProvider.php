<?php

namespace App\Providers;

use App\Services\PageService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(PageService::class, function ($app) {
            return new PageService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(PageService $pageService)
    {
        $url = Request::url();
        $pageUrl = basename($url);

        // Get folder name...
        $path = parse_url($url, PHP_URL_PATH);
        $folder = pathinfo($path, PATHINFO_DIRNAME);
        $folderName = basename($folder);

        if(in_array($path, ["main", "admin"])) {
            $accessType = "admin_page";
        } else if(in_array($path, ["user"])) {
            $accessType = "user_page";
        } else {
            $accessType = "auth_page";
        }    
        
        $getPage = $pageService->getPage($pageUrl, $accessType);
        $pageTitle = $getPage === NULL ? "" : $getPage->title;
        View::share('pageTitle', $pageTitle);

        View::composer('*', function ($view) {
            $pageService = new PageService();
            $view->with('allPageService', $pageService);
        });
    }

}