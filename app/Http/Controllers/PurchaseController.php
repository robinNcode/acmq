<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $purchases = \App\Models\Purchase::with(['supplier'])->orderBy('id', 'desc')->paginate(30);
        $suppliers = \Illuminate\Support\Facades\DB::table('suppliers')->orderBy('name')->get();
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        return view('purchases.index', compact('purchases', 'suppliers', 'branches'));
    }

    public function create()
    {
        $suppliers = \Illuminate\Support\Facades\DB::table('suppliers')->orderBy('name')->get();
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

        \App\Models\Purchase::create($data);

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('purchases.index');
    }

    public function edit(string $id)
    {
        $purchase = \App\Models\Purchase::findOrFail($id);
        $suppliers = \Illuminate\Support\Facades\DB::table('suppliers')->orderBy('name')->get();
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        return view('purchases.form', compact('purchase', 'suppliers', 'branches'));
    }

    public function update(Request $request, string $id)
    {
        $purchase = \App\Models\Purchase::findOrFail($id);

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

        $purchase->update($data);

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(string $id)
    {
        $purchase = \App\Models\Purchase::findOrFail($id);
        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
