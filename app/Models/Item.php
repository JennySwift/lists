<?php

namespace App\Models;

use App\Repositories\ItemsRepository;
use App\Traits\ForCurrentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Debugbar;
use Auth;

/**
 * Class Item
 * @package App\Models
 */
class Item extends Model
{
    use SoftDeletes;
    use ForCurrentUser;

    /**
     * @var array
     */
    protected $fillable = ['title', 'body', 'priority', 'favourite', 'pinned'];

    /**
     * The attributes that should be mutated to dates
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
//    protected $with = ['category'];

    /**
     * @var array
     */
//    protected $appends = ['path', 'has_children', 'path_to_item'];

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
        return $this->hasMany('\App\Models\Item', 'parent_id');
    }

    /**
     *
     * @return mixed
     */
    public function siblings()
    {
        if (!$this->parent) {
            return Item::where('user_id', Auth::user()->id)
                ->whereNull('parent_id')
                ->where('id', '!=', $this->id)
                ->get();
        }
        return Item::where('parent_id', $this->parent_id)
            ->where('id', '!=', $this->id)
            ->get();
    }

    /**
     *
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeOrder($query, $type)
    {
        return $query->orderBy($type, 'asc');
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
        return route('api.items.show', $this->id);
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
                if (count($parent->children) > 0) {
                    return $parent->children->last()->index + 1;
                }
                else {
                    return 0;
                }

            }
            else {
                return Item::where('user_id', Auth::user()->id)
                    ->whereNull('parent_id')->max('index') + 1;
            }
        }
    }

    /**
     *
     * @return array
     */
    public function transform()
    {
        $array = [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'title' => $this->title,
            'body' => $this->body,
            'index' => $this->index,
            'category_id' => $this->category_id,
            'priority' => $this->priority,
            'favourite' => $this->favourite,
            'pinned' => $this->pinned,
            'path' => $this->path,
            'path_to_item' => $this->path_to_item,
            'has_children' => $this->has_children,
            'category' => $this->category
        ];

        return $array;
    }
}
