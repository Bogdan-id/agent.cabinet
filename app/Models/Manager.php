<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $connection = 'default';
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'photo'
    ];


     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'bitrix_id'
    ];

    protected $casts = [
        //
    ];

    /**
     * Get the agent record associated with the user.
     */
    public function agent()
    {
        return $this->hasOne('App\Models\Manager');
    }
}
