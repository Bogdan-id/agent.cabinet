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
       'last_name',
       'first_name',
       'patronymic',
       'company_type',
       'company_name',
       'position',
       'passport_type_id',
       'inn',
       'birth',
       'card_number',
       'iban',
       'purpose_of_payment'
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
     * Get the manager record associated with the agent.
     */
    public function manager()
    {
        return $this->belongsTo('App\Models\Manager');
    }

    /**
     * Get the leasing requests records associated with the agent.
     */
    public function leasingRequests()
    {
        return $this->hasMany('App\Models\LeasingRequest');
    }

    /**
     * Get the commisions records associated with the agent.
     */
    public function commissions()
    {
        return $this->hasMany('App\Models\AgentCommission')->with('leasingRequest');
    }
}
