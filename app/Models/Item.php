<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = ['id', 'user_id', 'parent_id', 'order_number'];

    protected $appends = ['path', 'has_children', 'path_to_item'];

//    protected $with = ['children'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('\App\Models\Item');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany('\App\Models\Item', 'parent_id')
            ->orderBy('index', 'asc');
    }

    public function siblings()
    {
//        if ($this->parent) {
//            $siblings_and_item = $this->parent->children;
//        }
//        else {
//            $siblings_and_item = Item::whereNull('parent_id')->get();
//        }
//        return $siblings_and_item->except($this->id);
        if (!$this->parent) {
            return Item::whereNull('parent_id')
                ->where('id', '!=', $this->id)
                ->get();
        }
        return Item::where('parent_id', $this->parent_id)
            ->where('id', '!=', $this->id)
            ->get();
    }

    public function lastSibling()
    {
//        return last($this->siblings()->toArray());
        if (!$this->siblings()) {
            return false;
        }
        return $this->siblings()->last();
    }

//    public function lastSibling()
//    {
//        return $this->parent()-
//    }

    /**
     * Return the URL of the project
     * it needs to be called getFieldAttribute
     * @return string
     */
    public function getPathAttribute()
    {
        return route('items.show', $this->id);
    }

    public function getPathToItemAttribute()
    {
        $breadcrumb = $this->breadcrumb();
        return collect($breadcrumb)->lists('index');
    }

    /**
     * Does the item have any children?
     * @return string
     */
    public function gethasChildrenAttribute()
    {
        if (count($this->children()->get())) {
            return true;
        }

        return false;
    }

    /**
     *
     * @return array
     */
    public function breadcrumb()
    {
        $breadcrumb = [$this];

        $index = 0;

        while (!is_null($breadcrumb[$index]->parent()->first())) {
            $breadcrumb[] = $breadcrumb[$index]->parent()->first();
            $index++;
        }

//        $breadcrumb[] = ['title' => 'Home'];

        return array_reverse($breadcrumb);
    }

    /**
     * Same as breadcrumb method but without the item itself
     * @return array
     */
    public function ancestors()
    {
        $ancestors = [$this->parent()->first()];

        $index = 0;

        while (!is_null($ancestors[$index]->parent()->first())) {
            $ancestors[] = $ancestors[$index]->parent()->first();
            $index++;
        }

        $ancestors = array_reverse($ancestors);

        return collect($ancestors);
    }

    public function descendants()
    {
        $descendant_ids = [];

        $index = 0;

        while (true) {
            $item = Item::find($descendant_ids[$index]);

            //Add all the item's immediate children to the array
            foreach ($item->children()->lists('id') as $child_id) {
                $descendant_ids[] = $child_id;
            }
            $index++;
        }

        return $descendant_ids;

    }
}
