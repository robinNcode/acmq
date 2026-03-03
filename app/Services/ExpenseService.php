<?php
namespace App\Services;

use App\Repositories\Interfaces\ExpenseRepositoryInterface;

class ExpenseService
{
    protected $expenseRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function getPaginatedExpenses($perPage = 30)
    {
        return $this->expenseRepository->paginate($perPage);
    }

    public function getExpenseById($id)
    {
        return $this->expenseRepository->find($id);
    }

    public function createExpense(array $data)
    {
        return $this->expenseRepository->create($data);
    }

    public function updateExpense($id, array $data)
    {
        return $this->expenseRepository->update($id, $data);
    }

    public function deleteExpense($id)
    {
        return $this->expenseRepository->delete($id);
    }
}
