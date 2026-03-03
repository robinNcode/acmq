<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SaleService;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(Request $request)
    {
        $sales = $this->saleService->getPaginatedSales(30);
        $customers = $this->saleService->getCustomers();
        $branches = DB::table('branches')->orderBy('name')->get();
        $products = DB::table('products')->whereNull('deleted_at')->orderBy('name')->get();

        return view('sales.index', compact('sales', 'customers', 'branches', 'products'));
    }

    public function create()
    {
        $customers = $this->saleService->getCustomers();
        $branches = DB::table('branches')->orderBy('name')->get();
        $products = DB::table('products')->whereNull('deleted_at')->orderBy('name')->get();
        return view('sales.form', compact('customers', 'branches', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:sales,code|max:20',
            'branch_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'selling_date' => 'required|date',
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

        $this->saleService->createSale($data);

        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('sales.index');
    }

    public function edit(string $id)
    {
        $sale = $this->saleService->getSaleById($id);
        $customers = $this->saleService->getCustomers();
        $branches = DB::table('branches')->orderBy('name')->get();
        $products = DB::table('products')->whereNull('deleted_at')->orderBy('name')->get();

        return view('sales.form', compact('sale', 'customers', 'branches', 'products'));
    }

    public function update(Request $request, string $id)
    {
        $sale = $this->saleService->getSaleById($id);

        $data = $request->validate([
            'code' => 'required|string|max:20|unique:sales,code,' . $sale->id,
            'branch_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'selling_date' => 'required|date',
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

        $this->saleService->updateSale($id, $data);

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->saleService->deleteSale($id);

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }

    public function invoice(string $id)
    {
        $sale = $this->saleService->getSaleById($id);
        $sale->load(['customer', 'branch']);
        $products = DB::table('products')->whereNull('deleted_at')->get()->keyBy('id');

        return view('sales.invoice', compact('sale', 'products'));
    }
}


