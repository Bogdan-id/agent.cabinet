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
       'purpose_of_payment',
       'user_id',
       'oferta_accepted',
       'show_menu_commission'
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

    public function getNameAttribute()
    {
        return "{$this->last_name} {$this->first_name} {$this->patronymic}";  
    }

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

    /**
     * Get the notifications records associated with the agent.
     */
    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

    /**
     * Get the notifications records associated with the agent.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
