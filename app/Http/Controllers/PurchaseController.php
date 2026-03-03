<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PurchaseService;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index(Request $request)
    {
        $purchases = $this->purchaseService->getPaginatedPurchases(30);
        $suppliers = $this->purchaseService->getSuppliers();
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        return view('purchases.index', compact('purchases', 'suppliers', 'branches'));
    }

    public function create()
    {
        $suppliers = $this->purchaseService->getSuppliers();
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        return view('purchases.form', compact('suppliers', 'branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:purchases,code|max:20',
            'branch_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'purchase_date' => 'required|date',
            'total_price' => 'required|numeric',
            'discount' => 'required|numeric',
            'paid' => 'required|numeric',
            'due' => 'required|numeric',
            'product_info' => 'nullable|string',
        ]);

        if(!empty($data['product_info'])) {
            $data['product_info'] = json_decode($data['product_info'], true) ?? [];
        } else {
            $data['product_info'] = [];
        }

        $this->purchaseService->createPurchase($data);

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('purchases.index');
    }

    public function edit(string $id)
    {
        $purchase = $this->purchaseService->getPurchaseById($id);
        $suppliers = $this->purchaseService->getSuppliers();
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        return view('purchases.form', compact('purchase', 'suppliers', 'branches'));
    }

    public function update(Request $request, string $id)
    {
        $purchase = $this->purchaseService->getPurchaseById($id);

        $data = $request->validate([
            'code' => 'required|string|max:20|unique:purchases,code,' . $purchase->id,
            'branch_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'purchase_date' => 'required|date',
            'total_price' => 'required|numeric',
            'discount' => 'required|numeric',
            'paid' => 'required|numeric',
            'due' => 'required|numeric',
            'product_info' => 'nullable|string',
        ]);

        if(!empty($data['product_info'])) {
            $data['product_info'] = json_decode($data['product_info'], true) ?? [];
        } else {
            $data['product_info'] = [];
        }

        $this->purchaseService->updatePurchase($id, $data);

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->purchaseService->deletePurchase($id);

        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}

