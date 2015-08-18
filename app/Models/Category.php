<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package App\Models
 */
class Category extends Model
{
    /**
     * @var array
     */
    protected $appends = ['path'];

    protected $fillable = ['name'];

    /**
     * Return the URL of the resource
     * @return string
     */
    public function getPathAttribute()
    {
        return route('categories.show', $this->id);
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\App\User');
    }

//    public function getCategories()
//    {
//        return Category::where('user_id', Auth::user()->id)
//            ->orderBy('name', 'asc')
//            ->get();
//    }
}
