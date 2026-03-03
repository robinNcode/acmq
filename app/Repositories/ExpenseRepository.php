<?php
namespace App\Repositories;

use App\Models\Expense;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;

class ExpenseRepository extends BaseRepository implements ExpenseRepositoryInterface
{
    public function __construct(Expense $model)
    {
        parent::__construct($model);
    }

    public function paginate($perPage = 30)
    {
        return $this->model->with(['branch'])->orderBy('id', 'desc')->paginate($perPage);
    }
}
