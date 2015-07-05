<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * @package App
 */
class Item extends Model
{

    protected $guarded = ['id', 'user_id', 'parent_id'];

    protected $appends = ['path', 'has_children'];

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
        return $this->belongsTo('\App\Item');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany('\App\Item', 'parent_id');
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

}
