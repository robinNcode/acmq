<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\Interfaces\EmployeeRepositoryInterface;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    public function __construct(Employee $model)
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
