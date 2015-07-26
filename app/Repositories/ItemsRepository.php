<?php namespace App\Repositories;

use App\Models\Item;

/**
 * Class ItemsRepository
 */
class ItemsRepository {

    /**
     * Move an item up, keeping the same parent as before.
     * @param $parent_id
     * @param $new_index
     * @param $old_index
     */
    public function moveUp($parent_id, $new_index, $old_index)
    {
        Item::where('parent_id', $parent_id)
            ->where('index', '>=', $new_index)
            ->where('index', '<', $old_index)
            ->increment('index');
    }

    /**
     * Move an item down, keeping the same parent as before.
     * @param $parent_id
     * @param $new_index
     * @param $old_index
     */
    public function moveDown($parent_id, $new_index, $old_index)
    {
        Item::where('parent_id', $parent_id)
            ->where('index', '>', $old_index)
            ->where('index', '<=', $new_index)
            ->decrement('index');
    }
}