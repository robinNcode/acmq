<?php
namespace App\Services;

use App\Repositories\Interfaces\BranchRepositoryInterface;

class BranchService
{
    protected $branchRepository;

    public function __construct(BranchRepositoryInterface $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function getAllBranches()
    {
        return $this->branchRepository->all();
    }

    public function getPaginatedBranches($perPage = 30)
    {
        return $this->branchRepository->paginate($perPage);
    }

    public function getBranchById($id)
    {
        return $this->branchRepository->find($id);
    }

    public function createBranch(array $data)
    {
        return $this->branchRepository->create($data);
    }

    public function updateBranch($id, array $data)
    {
        return $this->branchRepository->update($id, $data);
    }

    public function deleteBranch($id)
    {
        return $this->branchRepository->delete($id);
    }
}
