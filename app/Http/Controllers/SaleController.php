<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sales = \App\Models\Sale::with(['customer'])->orderBy('id', 'desc')->paginate(30);
        $customers = \Illuminate\Support\Facades\DB::table('customers')->orderBy('name')->get();
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        return view('sales.index', compact('sales', 'customers', 'branches'));
    }

    public function create()
    {
        $customers = \Illuminate\Support\Facades\DB::table('customers')->orderBy('name')->get();
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

        \App\Models\Sale::create($data);

        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('sales.index');
    }

    public function edit(string $id)
    {
        $sale = \App\Models\Sale::findOrFail($id);
        $customers = \Illuminate\Support\Facades\DB::table('customers')->orderBy('name')->get();
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        return view('sales.form', compact('sale', 'customers', 'branches'));
    }

    public function update(Request $request, string $id)
    {
        $sale = \App\Models\Sale::findOrFail($id);

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

        $sale->update($data);

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(string $id)
    {
        $sale = \App\Models\Sale::findOrFail($id);
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}
