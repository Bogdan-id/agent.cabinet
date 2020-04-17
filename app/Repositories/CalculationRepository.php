<?php

namespace App\Articles\Repositories;

use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Repositories\BaseRepository;

class CalculationRepository extends BaseRepository implements ArticleRepositoryInterface
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