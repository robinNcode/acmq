<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $expenses = \App\Models\Expense::with(['branch'])->orderBy('id', 'desc')->paginate(30);
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        return view('expenses.index', compact('expenses', 'branches'));
    }

    public function create()
    {
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        return view('expenses.form', compact('branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'particulars' => 'required|string|max:255',
            'date' => 'required|date',
            'entry_by' => 'nullable|integer',
            'approved_by' => 'nullable|integer',
        ]);

        \App\Models\Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('expenses.index');
    }

    public function edit(string $id)
    {
        $expense = \App\Models\Expense::findOrFail($id);
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        return view('expenses.form', compact('expense', 'branches'));
    }

    public function update(Request $request, string $id)
    {
        $expense = \App\Models\Expense::findOrFail($id);

        $data = $request->validate([
            'branch_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'particulars' => 'required|string|max:255',
            'date' => 'required|date',
            'entry_by' => 'nullable|integer',
            'approved_by' => 'nullable|integer',
        ]);

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(string $id)
    {
        $expense = \App\Models\Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
