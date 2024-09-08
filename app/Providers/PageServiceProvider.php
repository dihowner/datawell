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
        // Binding PageService to the service container for dependency injection
        $this->app->singleton(PageService::class, function ($app) {
            return new PageService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(PageService $pageService)
    {
        $this->sharePageTitle($pageService);
        $this->sharePageService();
    }

    /**
     * Share the page title with all views based on the current URL and access type.
     */
    private function sharePageTitle(PageService $pageService)
    {
        $currentUrl = Request::url();
        $pageUrl = basename(parse_url($currentUrl, PHP_URL_PATH));

        // Default to 'login' if URL path is empty
        $pageUrl = empty($pageUrl) ? 'login' : $pageUrl;

        // Determine access type based on the URL path
        $accessType = $this->getAccessType($pageUrl);

        // Fetch page information based on URL and access type
        $page = $pageService->getPage($pageUrl, $accessType);
        $pageTitle = $page->title ?? env('APP_NAME');

        // Share page title with all views
        View::share('pageTitle', $pageTitle);
    }

    /**
     * Get the access type based on the URL path.
     */
    private function getAccessType($pageUrl)
    {
        $adminPages = ['main', 'admin'];
        $userPages = ['user'];

        if (in_array($pageUrl, $adminPages)) {
            return 'admin_page';
        }

        if (in_array($pageUrl, $userPages)) {
            return 'user_page';
        }

        return 'auth_page';
    }

    /**
     * Share the PageService instance with all views.
     */
    private function sharePageService()
    {
        View::composer('*', function ($view) {
            $view->with('allPageService', app(PageService::class));
        });
    }
}