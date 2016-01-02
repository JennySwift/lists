<?php namespace App\Repositories;

use App\Models\Item;
use Auth;
use Illuminate\Http\Request;

/**
 * Class ItemsRepository
 */
class ItemsRepository {

    /**
     * Transform a collection
     * @param $items
     * @return mixed
     */
    public function transform($items)
    {
        $array = [];
        foreach ($items as $item) {
            $array[] = $item->transform();
        }

        return $array;
    }

    /**
     *
     * @return mixed
     */
    public function getHomeItems()
    {
        return Item::where('user_id', Auth::user()->id)
            ->whereNull('parent_id')
            ->order('priority')
            ->get();
    }

    /**
     *
     * @return mixed
     */
    public function getFavourites()
    {
        return Item::where('user_id', Auth::user()->id)
            ->where('favourite', 1)
            ->get();
    }

    /**
     *
     * @param Request $request
     */
    public function moveItem(Request $request, $item)
    {
        $parent = Item::find($request->get('parent_id'));
        $old_index = $request->get('old_index');
        $new_index = $request->get('new_index');

        if ($request->get('new_parent')) {
            $new_parent = Item::find($request->get('new_parent_id'));

            $this->itemsRepository->moveToNewParent(
                $item,
                Item::find($request->get('old_parent_id')),
                $old_index,
                $new_parent,
                $new_index
            );
        }
        else {
            $this->itemsRepository->moveItemSameParent(
                $item,
                $old_index,
                $new_index,
                $parent
            );
        }
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