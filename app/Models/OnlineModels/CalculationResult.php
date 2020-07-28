<?php

namespace App\Models\OnlineModels;

use Illuminate\Database\Eloquent\Model;

class CalculationResult extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_online';

    protected $table = 'calculation_result';

    public $timestamp = true;

    protected $fillable = [
        'request_id',
        'request_data',
        'result_data',
        'type',
        'bitrix_type',
        'is_sent',
    ];
}
