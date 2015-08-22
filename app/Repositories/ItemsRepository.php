<?php namespace App\Repositories;

use App\Models\Item;
use Auth;

/**
 * Class ItemsRepository
 */
class ItemsRepository {

    public function getHomeItems()
    {
        return Item::where('user_id', Auth::user()->id)
            ->whereNull('parent_id')
            ->order('priority')
            ->get();
    }

    public function getFavourites()
    {
        return Item::where('user_id', Auth::user()->id)
            ->where('favourite', 1)
            ->get();
    }

    /**
     * For moving an item, keeping the same parent.
     * Update the indexes of the siblings as well as the item.
     * @param $old_index
     * @param $new_index
     * @param $parent
     * @param $item
     */
    public function moveItemSameParent($item, $old_index, $new_index, $parent)
    {
        $this->updateSiblingIndexes($old_index, $new_index, $parent);
        $item->updateIndex($new_index);
    }

    /**
     * For moving item up or down, keeping the same parent.
     * Update the indexes of the item's siblings.
     * @param $old_index
     * @param $new_index
     * @param $parent
     */
    public function updateSiblingIndexes($old_index, $new_index, $parent)
    {
        if ($new_index > $old_index) {
            $this->moveDown($parent, $new_index, $old_index);
        }
        else if ($new_index < $old_index) {
            $this->moveUp($parent, $new_index, $old_index);
        }
    }

    /**
     * Move items down, in order to move an item up,
     * keeping the same parent as before.
     * @param $parent
     * @param $new_index
     * @param $old_index
     */
    public function moveUp($parent, $new_index, $old_index)
    {
        if ($parent) {
            Item::where('parent_id', $parent->id)
                ->where('index', '>=', $new_index)
                ->where('index', '<', $old_index)
                ->increment('index');
        }
        else {
            Item::where('user_id', Auth::user()->id)
                ->whereNull('parent_id')
                ->where('index', '>=', $new_index)
                ->where('index', '<', $old_index)
                ->increment('index');
        }

    }

    /**
     * Move items up, in order to move an item down,
     * keeping the same parent as before.
     * @param $parent
     * @param $new_index
     * @param $old_index
     */
    public function moveDown($parent, $new_index, $old_index)
    {
        if ($parent) {
            Item::where('parent_id', $parent->id)
                ->where('index', '>', $old_index)
                ->where('index', '<=', $new_index)
                ->decrement('index');
        }
        else {
            Item::where('user_id', Auth::user()->id)
                ->whereNull('parent_id')
                ->where('index', '>', $old_index)
                ->where('index', '<=', $new_index)
                ->decrement('index');
        }

    }

    /**
     * Move item to a new parent
     * @param $item
     * @param $old_parent
     * @param $old_index
     * @param $new_parent
     * @param $new_index
     */
    public function moveToNewParent($item, $old_parent, $old_index, $new_parent, $new_index)
    {
        $this->moveOut($old_parent, $old_index);
        $this->moveIn($new_parent, $new_index);
        $item->moveToNewParent($new_parent, $new_index);
    }

    /**
     * For moving item to new parent. Move items in old parent up.
     * @param $old_parent
     * @param $old_index
     */
    public function moveOut($old_parent, $old_index)
    {
        Item::where('parent_id', $old_parent->id)
            ->where('index', '>', $old_index)
            ->decrement('index');
    }

    /**
     * For moving item to new parent. Make room for the new item.
     * @param $new_parent
     * @param $new_index
     */
    public function moveIn($new_parent, $new_index)
    {
        if ($new_parent) {
            Item::where('parent_id', $new_parent->id)
                ->where('index', '>=', $new_index)
                ->increment('index');
        }
        else {
            //Moving home
            Item::where('user_id', Auth::user()->id)
                ->whereNull('parent_id')
                ->where('index', '>=', $new_index)
                ->increment('index');
        }
    }
}