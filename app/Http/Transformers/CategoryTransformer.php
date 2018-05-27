<?php

namespace App\Http\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

/**
 * Class CategoryTransformer
 */
class CategoryTransformer extends TransformerAbstract
{
    /**
     * @param Category $category
     * @return array
     */
    public function transform(Category $category)
    {
        $array = [
            'id' => $category->id,
            'name' => $category->name,
        ];

        return $array;
    }

}