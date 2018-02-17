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



        $array = [
            'id' => $item->id,
            'parent_id' => $item->parent_id,
            'title' => $item->title,
            'body' => $item->body,
            'index' => $item->index,
            'category_id' => $item->category_id,
            'priority' => $item->priority,
            'urgency' => $item->urgency,
            'favourite' => $item->favourite,
            'pinned' => $item->pinned,
            'path' => $item->path,
            'has_children' => $item->has_children,
            'category' => $item->category->transform(),
            'alarm' => $item->alarm,
            'timeLeft' => null,
            'notBefore' => $item->not_before,
            'recurringUnit' => $item->recurring_unit,
            'recurringFrequency' => $item->recurring_frequency,
            'deletedAt' => $item->deleted_at,
            'canBeRestored' => $item->canBeRestored(),
            //For Vue
            'children' => [
                'data' => [],
                'pagination' => []
            ],
            //So I can display the item crossed out when it is being deleted, before it has been deleted
            'deleting' => false
        ];

        if ($item->trashed()) {
            $array['deleted_at'] = $item->deleted_at->format('Y-m-d H:i:s');
        }

        return $array;
    }

}