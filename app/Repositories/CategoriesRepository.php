<?php namespace App\Repositories;

use App\Models\Category;
use Auth;

/**
 * Class CategoriesRepository
 */
class CategoriesRepository {

    /**
     *
     * @return mixed
     */
    public function getCategories()
    {
        return Category::where('user_id', Auth::user()->id)
            ->orderBy('name', 'asc')
            ->get();
    }
}