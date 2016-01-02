<?php

namespace App\Http\Transformers;

use App\Models\Item;
use League\Fractal\TransformerAbstract;

/**
 * Class ItemTransformer
 */
class ItemTransformer extends TransformerAbstract
{
    /**
     * @param Item $item
     * @return array
     */
    public function transform(Item $item)
    {
//        $array = [
//            'id' => $item->id,
//            'parent_id' => $item->id,
//            'title' => $item->title,
//            'body' => $item->body,
//            'index' => $item->index,
//            'category_id' => $item->category_id,
//            'priority' => $item->priority,
//            'favourite' => $item->favourite,
//            'pinned' => $item->pinned,
//        ];
//
//        return $array;
    }

}