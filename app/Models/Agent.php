<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
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
      'oferta_accepted'
    ];

    protected $casts = [
      //
    ];

    /**
     * Get the agent record associated with the user.
     */
    public function manager()
    {
        return $this->belongsTo('App\Models\Manager');
    }

}
