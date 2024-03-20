<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;

class CategoryController extends Controller {
    protected $categoryService;
    
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getCategory($categoryName) {
        return $this->categoryService->getCategoryByName($categoryName);
    }

    public function getSubCategory($categoryName) {
        return $this->categoryService->getSubCategories($categoryName);
    }

}