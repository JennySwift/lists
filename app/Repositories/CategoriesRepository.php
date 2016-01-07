<?php namespace App\Repositories;

use App\Models\Category;
use Auth;

/**
 * Class CategoriesRepository
 */
class CategoriesRepository {

    /**
     * Transform a collection
     * @param $categories
     * @return array
     */
    public function transform($categories)
    {
        $array = [];
        foreach ($categories as $category) {
            $array[] = $category->transform();
        }

        return $array;
    }

    /**
     *
     * @return mixed
     */
//    public function getCategories()
//    {
//        return Category::where('user_id', Auth::user()->id)
//            ->orderBy('name', 'asc')
//            ->get();
//    }
}