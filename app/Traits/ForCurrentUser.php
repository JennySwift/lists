<?php  namespace App\Traits;

use App\Exceptions\NotLoggedInException;
use Auth;

/**
 * Define you user (relationship) related methods here
 * @package App\Traits\Models\Scopes
 */
trait ForCurrentUser {

    /**
     * User relationship
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @param $query
     * @param null $table
     * @return mixed
     */
    public function scopeForCurrentUser($query, $table = null)
    {
        if (Auth::check()) {
            if (is_null($table)) {
                return $query->whereUserId(Auth::user()->id);
            }
            return $query->where($table.'.user_id', Auth::user()->id);
        }

        throw new NotLoggedInException;
    }

}