<?php

namespace App\Services;

use App\Repositories\Interfaces\EmployeeRepositoryInterface;

class EmployeeService
{
    protected $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function getPaginatedEmployees($perPage = 30)
    {
        $branchId = auth()->check() && !auth()->user()->isAdmin() ? auth()->user()->branch_id : null;
        return $this->employeeRepository->paginate($perPage, $branchId);
    }

    public function getEmployeeById($id)
    {
        return $this->employeeRepository->find($id);
    }

    public function createEmployee(array $data)
    {
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        return $this->employeeRepository->create($data);
    }

    public function updateEmployee($id, array $data)
    {
        return $this->employeeRepository->update($id, $data);
    }

    public function deleteEmployee($id)
    {
        return $this->employeeRepository->delete($id);
    }
}
