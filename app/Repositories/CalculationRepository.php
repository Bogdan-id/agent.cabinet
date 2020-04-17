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
        return $this->model->create($attributes);
    }
}