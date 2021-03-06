<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeasingRequest extends Model
{  
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bitrix_id',
        'agent_id',
        'calculation_id',
        'client_type_id',
        'last_name',
        'first_name',
        'patronymic',
        'leasing_object',
        'advance',
        'leasing_object',
        'advance',
        'leasing_term',
        'leasing_amount',
        'graph_type',
        'legal_info',
        'documents',
        'email',
        'phone',
        'region',
        'price_brutto'
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
        'legal_info' => 'array',
        'documents' => 'array',
    ];
    
    public function getNameAttribute()
    {
        return "{$this->last_name} {$this->first_name} {$this->patronymic}";  
    }

    public function calculation()
    {
        return $this->belongsTo('App\Models\Calculation');
    }
}
