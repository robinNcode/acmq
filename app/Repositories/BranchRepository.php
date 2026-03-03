<?php
namespace App\Repositories;

use App\Models\Branch;
use App\Repositories\Interfaces\BranchRepositoryInterface;

class BranchRepository extends BaseRepository implements BranchRepositoryInterface
{
    public function __construct(Branch $model)
    {
        parent::__construct($model);
    }

    public function paginate($perPage = 30)
    {
        return $this->model->orderBy('id', 'desc')->paginate($perPage);
    }
}
