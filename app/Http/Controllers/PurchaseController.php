<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\DB;

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
        $branches = DB::table('branches')->orderBy('name')->get();
        $products = DB::table('products')->whereNull('deleted_at')->orderBy('name')->get();
        return view('purchases.index', compact('purchases', 'suppliers', 'branches', 'products'));
    }

    public function create()
    {
        $suppliers = $this->purchaseService->getSuppliers();
        $branches = DB::table('branches')->orderBy('name')->get();
        $products = DB::table('products')->whereNull('deleted_at')->orderBy('name')->get();
        return view('purchases.form', compact('suppliers', 'branches', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:purchases,code|max:20',
            'branch_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'purchase_date' => 'required|date',
            'discount' => 'required|numeric',
            'paid' => 'required|numeric',
            'due' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $data['product_info'] = $data['items'];
        $data['total_price'] = collect($data['items'])->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });
        unset($data['items']);

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
        $branches = DB::table('branches')->orderBy('name')->get();
        $products = DB::table('products')->whereNull('deleted_at')->orderBy('name')->get();
        return view('purchases.form', compact('purchase', 'suppliers', 'branches', 'products'));
    }

    public function update(Request $request, string $id)
    {
        $purchase = $this->purchaseService->getPurchaseById($id);

        $data = $request->validate([
            'code' => 'required|string|max:20|unique:purchases,code,' . $purchase->id,
            'branch_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'purchase_date' => 'required|date',
            'discount' => 'required|numeric',
            'paid' => 'required|numeric',
            'due' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $data['product_info'] = $data['items'];
        $data['total_price'] = collect($data['items'])->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });
        unset($data['items']);

        $this->purchaseService->updatePurchase($id, $data);

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->purchaseService->deletePurchase($id);

        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
