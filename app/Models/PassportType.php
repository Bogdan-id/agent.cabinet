<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassportType extends Model
{   
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //
    ];


     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      //
    ];

    protected $casts = [
        //
    ];
}
