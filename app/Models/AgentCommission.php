<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentCommission extends Model
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
       //
     ];
 
     protected $casts = [
       //
     ];

     
    public function leasingRequest()
    {
        return $this->belongsTo('App\Models\LeasingRequest', 'leasing_requests_id');
    }
}
