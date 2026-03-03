<?php
namespace App\Repositories;

use App\Models\Sale;
use App\Repositories\Interfaces\SaleRepositoryInterface;

class SaleRepository extends BaseRepository implements SaleRepositoryInterface
{
    public function __construct(Sale $model)
    {
        parent::__construct($model);
    }

    public function paginate($perPage = 30)
    {
        return $this->model->with(['customer', 'branch'])->orderBy('id', 'desc')->paginate($perPage);
    }
}
