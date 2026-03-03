<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountService;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index()
    {
        $accounts = $this->accountService->getAllAccounts();
        return view('accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:accounts,code',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'branch_id' => 'required|integer'
        ]);

        $this->accountService->createAccount($validated);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function update(Request $request, string $id)
    {
        $account = $this->accountService->getAccountById($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:accounts,code,' . $account->id,
            'type' => 'required|in:asset,liability,equity,income,expense',
            'branch_id' => 'required|integer'
        ]);

        $this->accountService->updateAccount($id, $validated);

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy(string $id)
    {
        $success = $this->accountService->deleteAccount($id);
        
        if (!$success) {
            return redirect()->route('accounts.index')->with('error', 'Cannot delete account with existing journal entries.');
        }

        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
    }
}

