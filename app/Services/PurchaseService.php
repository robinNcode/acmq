<?php
namespace App\Services;

use App\Repositories\Interfaces\PurchaseRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    protected $purchaseRepository;

    public function __construct(PurchaseRepositoryInterface $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }

    public function getPaginatedPurchases($perPage = 30)
    {
        return $this->purchaseRepository->paginate($perPage);
    }

    public function getPurchaseById($id)
    {
        return $this->purchaseRepository->find($id);
    }

    public function createPurchase(array $data)
    {
        return $this->purchaseRepository->create($data);
    }

    public function updatePurchase($id, array $data)
    {
        return $this->purchaseRepository->update($id, $data);
    }

    public function deletePurchase($id)
    {
        return $this->purchaseRepository->delete($id);
    }
    
    public function getSuppliers()
    {
        return DB::table('suppliers')->orderBy('name')->get();
    }
}
