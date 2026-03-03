<?php
namespace App\Repositories;

use App\Models\Purchase;
use App\Repositories\Interfaces\PurchaseRepositoryInterface;

class PurchaseRepository extends BaseRepository implements PurchaseRepositoryInterface
{
    public function __construct(Purchase $model)
    {
        parent::__construct($model);
    }

    public function paginate($perPage = 30)
    {
        return $this->model->with(['supplier'])->orderBy('id', 'desc')->paginate($perPage);
    }
}
