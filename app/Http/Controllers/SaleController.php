<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SaleService;

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
        // Just directly fetching branches mapping to DB here to avoid cross-domain DI
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        
        return view('sales.index', compact('sales', 'customers', 'branches'));
    }

    public function create()
    {
        $customers = $this->saleService->getCustomers();
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        return view('sales.form', compact('customers', 'branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:sales,code|max:20',
            'branch_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'selling_date' => 'required|date',
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
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        
        return view('sales.form', compact('sale', 'customers', 'branches'));
    }

    public function update(Request $request, string $id)
    {
        $sale = $this->saleService->getSaleById($id);

        $data = $request->validate([
            'code' => 'required|string|max:20|unique:sales,code,' . $sale->id,
            'branch_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'selling_date' => 'required|date',
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

        $this->saleService->updateSale($id, $data);

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->saleService->deleteSale($id);

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}

