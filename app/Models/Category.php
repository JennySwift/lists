<?php

namespace App\Models;

use App\Traits\ForCurrentUser;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package App\Models
 */
class Category extends Model
{
    use ForCurrentUser;

    /**
     * @var array
     */
    protected $appends = ['path'];

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Return the URL of the resource
     * @return string
     */
    public function getPathAttribute()
    {
        return route('api.categories.show', $this->id);
    }

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
     * @return array
     */
    public function transform()
    {
        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
        ];

        return $array;
    }
}
