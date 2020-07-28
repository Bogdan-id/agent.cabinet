<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agent_id',  
        'request_id', 
        'result_data', 
        'request_data',
        'full_request_data',
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
        'request_data' => 'array',
        'result_data' => 'array',
        'full_request_data' => 'array',
    ];

    public function leasingRequest()
    {
        return $this->hasOne('App\Models\LeasingRequest');
    }
}
