<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CalculationRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Calculation;

class CalculationRepository extends BaseRepository implements CalculationRepositoryInterface
{
    protected $model;
  
    /**
     * CalculationRepository constructor.
     * @param Calculation $calculation
     */
    public function __construct(Calculation $calculation)
    {
        $this->model = $calculation;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $this->model->agent_id = $attributes['request_data']['agent_id'];
        $this->model->request_id = $attributes['result_data']['requestId'];
        $this->model->result_data = json_encode($attributes['result_data']);
        $this->model->request_data = json_encode($attributes['request_data']);
        $this->model->save();

        return $this->model;
    }
  
}