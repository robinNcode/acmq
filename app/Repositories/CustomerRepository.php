<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    public function all(?int $branchId = null)
    {
        $query = $this->model->query();
        if ($branchId !== null) {
            $query->where('branch_id', $branchId);
        }
        return $query->get();
    }

    public function paginate(int $perPage = 30, ?int $branchId = null)
    {
        $query = $this->model->query();
        if ($branchId !== null) {
            $query->where('branch_id', $branchId);
        }
        return $query->paginate($perPage);
    }
}
