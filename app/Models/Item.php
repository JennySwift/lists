<?php

namespace App\Models;

use App\Repositories\ItemsRepository;
use Illuminate\Database\Eloquent\Model;
use Debugbar;

/**
 * Class Item
 * @package App\Models
 */
class Item extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id', 'user_id', 'parent_id', 'order_number'];

    /**
     * @var array
     */
    protected $with = ['category'];

    /**
     * @var array
     */
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany('\App\Models\Item', 'parent_id')
            ->orderBy('index', 'asc');
//            ->get();
    }

    /**
     *
     * @return mixed
     */
    public function siblings()
    {
        if (!$this->parent) {
            return Item::whereNull('parent_id')
                ->where('id', '!=', $this->id)
                ->get();
        }
        return Item::where('parent_id', $this->parent_id)
            ->where('id', '!=', $this->id)
            ->get();
    }

    /**
     *
     * @return bool
     */
    public function lastSibling()
    {
        if (!$this->siblings()) {
            return false;
        }
        return $this->siblings()->last();
    }

    /**
     * Return the URL of the project
     * it needs to be called getFieldAttribute
     * @return string
     */
    public function getPathAttribute()
    {
        return route('items.show', $this->id);
    }

    /**
     *
     * @return static
     */
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

    /**
     *
     * @return array
     */
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

    /**
     *
     * @param $new_index
     */
    public function updateIndex($new_index)
    {
        $this->index = $new_index;
        $this->save();
    }

    /**
     *
     * @param $new_parent
     * @param $new_index
     */
    public function moveToNewParent($new_parent, $new_index)
    {
        $this->parent_id = $this->calculateParentId($new_parent);
        $this->index = $this->calculateIndex($new_index, $new_parent);
        $this->save();
    }

    /**
     *
     * @param $new_parent
     * @return null
     */
    public function calculateParentId($new_parent)
    {
        if ($new_parent) {
            return $new_parent->id;
        }
        else {
            //Item is being moved to home (no parent)
            return null;
        }
    }

    /**
     * Calculate what the index of an item should be if it is not specified,
     * in order to add it as the last child of the parent.
     * @param $new_index
     * @param $parent
     * @return mixed
     */
    public function calculateIndex($new_index, $parent)
    {
        if (isset($new_index)) {
            return $new_index;
        }
        else {
            if ($parent) {
                return $parent->children->last()->index + 1;
            }
            else {
                return Item::whereNull('parent_id')->max('index') + 1;
            }
        }
    }
}
