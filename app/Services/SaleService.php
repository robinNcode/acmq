<?php
namespace App\Services;

use App\Repositories\Interfaces\SaleRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SaleService
{
    protected $saleRepository;

    public function __construct(SaleRepositoryInterface $saleRepository)
    {
        $this->saleRepository = $saleRepository;
    }

    public function getPaginatedSales($perPage = 30)
    {
        return $this->saleRepository->paginate($perPage);
    }

    public function getSaleById($id)
    {
        return $this->saleRepository->find($id);
    }

    public function createSale(array $data)
    {
        return $this->saleRepository->create($data);
    }

    public function updateSale($id, array $data)
    {
        return $this->saleRepository->update($id, $data);
    }

    public function deleteSale($id)
    {
        return $this->saleRepository->delete($id);
    }
    
    public function getCustomers()
    {
        return DB::table('customers')->orderBy('name')->get();
    }
}
