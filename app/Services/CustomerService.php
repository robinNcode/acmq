<?php

namespace App\Services;

use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerService
{
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getPaginatedCustomers($perPage = 30)
    {
        $branchId = auth()->check() && !auth()->user()->isAdmin() ? auth()->user()->branch_id : null;
        return $this->customerRepository->paginate($perPage, $branchId);
    }

    public function getCustomerById($id)
    {
        return $this->customerRepository->find($id);
    }

    public function createCustomer(array $data)
    {
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        return $this->customerRepository->create($data);
    }

    public function updateCustomer($id, array $data)
    {
        return $this->customerRepository->update($id, $data);
    }

    public function deleteCustomer($id)
    {
        return $this->customerRepository->delete($id);
    }
}
