<?php

namespace App\Repositories\Interfaces;

interface CustomerRepositoryInterface
{
    public function all(?int $branchId = null);
    public function paginate(int $perPage = 30, ?int $branchId = null);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
