<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username'
    ];



     /**
     * The posts that have been liked by the fan.
     */
    public function posts()
    {
        return $this->belongsToMany('App\Post','fan_post')->withTimestamps();
    }
}
