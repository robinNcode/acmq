<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExpenseService;

class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index(Request $request)
    {
        $expenses = $this->expenseService->getPaginatedExpenses(30);
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

        $this->expenseService->createExpense($data);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('expenses.index');
    }

    public function edit(string $id)
    {
        $expense = $this->expenseService->getExpenseById($id);
        $branches = \Illuminate\Support\Facades\DB::table('branches')->orderBy('name')->get();
        return view('expenses.form', compact('expense', 'branches'));
    }

    public function update(Request $request, string $id)
    {
        $expense = $this->expenseService->getExpenseById($id);

        $data = $request->validate([
            'branch_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'particulars' => 'required|string|max:255',
            'date' => 'required|date',
            'entry_by' => 'nullable|integer',
            'approved_by' => 'nullable|integer',
        ]);

        $this->expenseService->updateExpense($id, $data);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->expenseService->deleteExpense($id);

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}

