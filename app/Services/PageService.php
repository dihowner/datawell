<?php
namespace App\Services;

use Exception;
use App\Models\Pages;

class PageService  {

    public function getPage($page, $accessType = "user_page") {
        try {
            $pageResult = Pages::where('id', $page)
                                ->where('access_type', $accessType)
                                ->Orwhere('slug', $page)->first();
            return $pageResult;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function groupPagesMenu($accessType) {
        try {
            $pageResult = Pages::where('access_type', $accessType)
                                ->select('menu', 'icon')
                                ->distinct()
                                ->groupBy('menu', 'icon')->get();
            $menu = [];
            if(count($pageResult) > 0) {
                foreach($pageResult as $groupIndex => $groupMenu) {
                    if($groupMenu['menu'] != "") {
                        $menu['menuName'][] = $groupMenu['menu'];
                        $menu['menuIcon'][] = $groupMenu['icon'];
                    }
                }
            }
            return $menu;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function getMenuLink($accessType, $menuName) {
        try {
            if($accessType == "") {
                $menuResult = Pages::where('menu', $menuName)->get();
            }
            else {
                $menuResult = Pages::where('access_type', $accessType)->where('menu', $menuName)->get();
            }

            $pages = [];
            if(count($menuResult) > 0) {
                foreach($menuResult as $menuresult) {
                    $pages['title'][] = $menuresult['title'];
                    $pages['slug'][] = $menuresult['slug'];
                }
            }
            return $pages;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllPages($accessType = '') {
        try {
            if($accessType == "") {
                $pageResults = Pages::get();
            }
            else {
                $pageResults = Pages::where('access_type', $accessType)->get();
            }

            if(count($pageResults) > 0) {
                return $pageResults;
            }
            return false;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function getUserPages() {
        $allPages = $this->getAllPages('user_page');
        return $allPages;
    }
}