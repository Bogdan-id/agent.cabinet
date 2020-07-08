<?php

namespace App\Repositories;

use App\Repositories\Interfaces\LeasingRequestRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\LeasingRequest;

class LeasingRequestRepository extends BaseRepository implements LeasingRequestRepositoryInterface
{
    protected $model;
  
    /**
     * CalculationRepository constructor.
     * @param LeasingRequest $leasingRequest
     */
    public function __construct(LeasingRequest $leasingRequest)
    {
        $this->model = $leasingRequest;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $this->model->agent_id = $attributes['agentId'];
        $this->model->calculation_id = $attributes['calculationId'];
        $this->model->client_type_id = $attributes['clientTypeId'];
        $this->model->first_name = $attributes['firstName'];
        $this->model->last_name = $attributes['lastName'];
        $this->model->patronymic = $attributes['patronymic'];
        $this->model->region = $attributes['region'];
        $this->model->leasing_object = $attributes['leasingObject'];
        $this->model->advance = $attributes['advance'];
        $this->model->leasing_term = $attributes['leasingTerm'];
        $this->model->leasing_amount = $attributes['leasingAmount'];
        $this->model->graph_type = $attributes['graphType'];
        $this->model->legal_info = $attributes['legalInfo'];
        if($attributes['clientTypeId'] === 1){
            $this->model->email = $attributes['email'];
            $this->model->phone = $attributes['phone'];
        }
        $this->model->documents = $attributes['documents'];

        $this->model->save();

        return $this->model;
    }
  
}