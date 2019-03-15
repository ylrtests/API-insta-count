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
        'name', 'price', 'category_id', 'quantity', 'status'
    ];
}
