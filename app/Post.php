<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_insta', 'date', 'updated_at'
    ];


    /**
     * The fans that liked the post.
     */
    public function fans()
    {
        return $this->belongsToMany('App\Fan','fan_post')->withTimestamps();
    }
}
