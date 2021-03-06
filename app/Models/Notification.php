<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{ 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status'
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

     protected $attributes = [
      'status' => 'not_checked'
  ];
}
