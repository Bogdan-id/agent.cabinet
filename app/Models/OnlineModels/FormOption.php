<?php

namespace App\Models\OnlineModels;

use Illuminate\Database\Eloquent\Model;

class FormOption extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_online';

    protected $table = 'form_option';
}
