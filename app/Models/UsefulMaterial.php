<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsefulMaterial extends Model
{   
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'useful_materials_category_id',
        'title_image',
        'title',
        'content',
        'slug'
    ];
}
