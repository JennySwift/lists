<?php namespace App\Repositories;

use App\Models\Item;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * Class ItemsRepository
 */
class ItemsRepository {

    /**
     *
     * @param Item $item
     * @return Item
     */
    public function updateNextTimeForRecurringItem(Item $item)
    {
        $notBeforeTime = Carbon::createFromFormat('Y-m-d H:i:s', $item->not_before);

        $notBeforeTime = $this->calculateNotBeforeTime($item, $notBeforeTime);

        //Make the not before time for the item later (according to the item's recurring unit and frequency) until it is on or after the current time.
        while ($notBeforeTime < Carbon::now()) {
            $notBeforeTime = $this->calculateNotBeforeTime($item, $notBeforeTime);
        }

        $item->not_before = $notBeforeTime->format('Y-m-d H:i:s');
        $item->save();

        return $item;
    }

    /**
     *
     * @param Item $item
     * @param Carbon $notBeforeTime
     * @return Carbon
     */
    private function calculateNotBeforeTime(Item $item, Carbon $notBeforeTime)
    {
        switch($item->recurring_unit) {
            case "minute":
                $notBeforeTime->addMinutes($item->recurring_frequency);
                break;

            case "hour":
                $notBeforeTime->addHours($item->recurring_frequency);
                break;

            case "day":
                $notBeforeTime->addDays($item->recurring_frequency);
                break;

            case "week":
                $notBeforeTime->addWeeks($item->recurring_frequency);
                break;

            case "month":
                $notBeforeTime->addMonths($item->recurring_frequency);
                break;

            case "year":
                $notBeforeTime->addYears($item->recurring_frequency);
                break;
        }

        return $notBeforeTime;
    }

    /**
     * For preventing duplicate entries if I have the app open in two tabs when a user submits feedback
     * @param Request $request
     * @return mixed
     */
    public function itemAlreadyExists(Request $request)
    {
        //Set the parent id to null if the value is 'none.'
        $parentId = $request->get('parent_id') === 'none' ? null : $request->get('parent_id');
        $item = Item::forCurrentUser()
            ->where('title', $request->get('title'))
            ->where('body', $request->get('body'))
            ->where('category_id', $request->get('category_id'))
            ->where('parent_id', $parentId)
            ->first();

        return $item;
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function getItems(Request $request)
    {
        //Sort first by priority, then by notBefore, then by category name, then by id
        $query = Item::forCurrentUser()
           ->whereNull('parent_id');

        return $this->createQuery($query, $request);
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
//    public function getFilteredItems(Request $request)
//    {
//        $field = $request->get('field') ? $request->get('field') : 'title';
//        $max = $request->get('max') ? $request->get('max') : Config::get('filters.max');
//        return Item::forCurrentUser()
//            ->where($field, 'LIKE', '%' . $request->get('filter') . '%')
//            ->paginate($max);
//    }

    /**
     *
     * @param Item $item
     * @param Request $request
     * @return mixed
     */
    public function getChildren(Item $item, Request $request)
    {
        $query = $item->children();

        return $this->createQuery($query, $request);
    }

    /**
     *
     * @param $query
     * @param Request $request
     * @return mixed
     */
    private function createQuery($query, Request $request)
    {
        $max = $request->get('max') ? $request->get('max') : Config::get('filters.max');

//        if ($request->has('parent_id')) {
//            $query = $query->where('parent_id', $request->get('parent_id'));
//        }
//        else {
//            $query = $query->whereNull('parent_id');
//        }

        if ($request->has('title')) {
            $query = $query->where('title', 'LIKE', '%' . $request->get('title') . '%');
        }

        if ($request->has('body')) {
            $query = $query->where('body', 'LIKE', '%' . $request->get('body') . '%');
        }

        if ($request->has('priority')) {
            $query = $query->where('priority', $request->get('priority'));
        }

        if ($request->has('min_priority')) {
            $query = $query->where('priority', '<=', $request->get('min_priority'));
        }

        if ($request->has('category_id')) {
            $query = $query->where('category_id', $request->get('category_id'));
        }

        if ($request->has('not_before')) {
            $query = $query->whereDate('not_before', $request->get('not_before'));
        }

        //Do not get items that have a not before time in the future
        if ($request->has('with_future_items') && $request->get('with_future_items') == "false") {
            $query = $query->where(function ($query) {
                $query->whereDate('not_before', '<=', Carbon::now()->format('Y-m-d'))
                    ->orWhereNull('not_before');
            });
        }


        if ($request->has('with_trashed')) {
            $query = $query->withTrashed();
        }

        $query = $query->orderBy('priority', 'asc')
            ->orderByRaw('`not_before` IS NULL')
            ->orderBy('not_before', 'asc')
            ->orderBy('category_id', 'asc')
            ->orderBy('id', 'desc')
            ->paginate($max);

//        dd($query->toSql());
        return $query;
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function getTrashed(Request $request)
    {
        $max = $request->get('max') ? $request->get('max') : Config::get('filters.max');

        return Item::forCurrentUser()
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate($max);
    }

    /**
     *
     * @return mixed
     */
    public function getFavourites()
    {
        return Item::forCurrentUser()
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
            Item::forCurrentUser()
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
            Item::forCurrentUser()
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
            Item::forCurrentUser()
                ->whereNull('parent_id')
                ->where('index', '>=', $new_index)
                ->increment('index');
        }
    }
}