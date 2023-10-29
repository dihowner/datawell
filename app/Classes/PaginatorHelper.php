<?php
namespace App\Classes;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginatorHelper{
    
    public static function createPaginator(Collection $items, $perPage, $currentPage, $path){
        $total = $items->count();

        $paginator = new LengthAwarePaginator(
            $items->forPage($currentPage, $perPage),
            $total,
            $perPage,
            $currentPage,
            ['path' => $path]
        );

        return $paginator;
    }
}

?>