<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Journal::with(['branch', 'entries.account'])
            ->whereIn('reference_type', ['receive', 'payment', 'jv'])
            ->orderByDesc('date')
            ->orderByDesc('id');

        if ($request->filled('type')) {
            $query->where('reference_type', $request->type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $vouchers = $query->paginate(20)->withQueryString();

        return view('vouchers.index', compact('vouchers'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'jv');
        if (!in_array($type, ['receive', 'payment', 'jv'], true)) {
            $type = 'jv';
        }

        $branches = Branch::orderBy('name')->get(['id', 'name']);
        $accounts = Account::orderBy('code')->orderBy('name')->get(['id', 'code', 'name']);

        return view('vouchers.create', compact('type', 'branches', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['receive', 'payment', 'jv'])],
            'date' => ['required', 'date'],
            'branch_id' => ['required', 'exists:branches,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'cash_account_id' => ['nullable', 'exists:accounts,id'],
            'counterparty_account_id' => ['nullable', 'exists:accounts,id'],
            'amount' => ['nullable', 'numeric', 'min:0.01'],
            'lines' => ['nullable', 'array'],
            'lines.*.account_id' => ['nullable', 'exists:accounts,id'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0'],
        ]);

        $type = $validated['type'];
        $lines = [];

        if (in_array($type, ['receive', 'payment'], true)) {
            $amount = (float) ($validated['amount'] ?? 0);
            if ($amount <= 0) {
                return back()->withErrors(['amount' => 'Amount must be greater than zero.'])->withInput();
            }

            $cashAccountId = (int) ($validated['cash_account_id'] ?? 0);
            $counterpartyAccountId = (int) ($validated['counterparty_account_id'] ?? 0);
            if ($cashAccountId <= 0 || $counterpartyAccountId <= 0) {
                return back()->withErrors(['cash_account_id' => 'Both accounts are required for Receive/Payment vouchers.'])->withInput();
            }
            if ($cashAccountId === $counterpartyAccountId) {
                return back()->withErrors(['counterparty_account_id' => 'Cash/Bank and Counterparty accounts must be different.'])->withInput();
            }

            if ($type === 'receive') {
                $lines[] = ['account_id' => $cashAccountId, 'debit' => $amount, 'credit' => 0];
                $lines[] = ['account_id' => $counterpartyAccountId, 'debit' => 0, 'credit' => $amount];
            } else {
                $lines[] = ['account_id' => $counterpartyAccountId, 'debit' => $amount, 'credit' => 0];
                $lines[] = ['account_id' => $cashAccountId, 'debit' => 0, 'credit' => $amount];
            }
        } else {
            foreach (($validated['lines'] ?? []) as $line) {
                $accountId = (int) ($line['account_id'] ?? 0);
                $debit = (float) ($line['debit'] ?? 0);
                $credit = (float) ($line['credit'] ?? 0);
                if ($accountId > 0 && ($debit > 0 || $credit > 0)) {
                    $lines[] = ['account_id' => $accountId, 'debit' => $debit, 'credit' => $credit];
                }
            }

            if (count($lines) < 2) {
                return back()->withErrors(['lines' => 'JV requires at least two valid lines.'])->withInput();
            }
        }

        $totalDebit = array_sum(array_map(function ($line) {
            return (float) $line['debit'];
        }, $lines));
        $totalCredit = array_sum(array_map(function ($line) {
            return (float) $line['credit'];
        }, $lines));
        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            return back()->withErrors(['lines' => 'Voucher is not balanced. Total debit and credit must match.'])->withInput();
        }

        $voucher = DB::transaction(function () use ($validated, $type, $lines) {
            $journal = Journal::create([
                'branch_id' => $validated['branch_id'],
                'reference_type' => $type,
                'reference_id' => null,
                'date' => $validated['date'],
                'description' => $validated['description'] ?? null,
                'created_by' => 1,
            ]);

            foreach ($lines as $line) {
                $journal->entries()->create([
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'],
                    'credit' => $line['credit'],
                ]);
            }

            return $journal;
        });

        return redirect()->route('vouchers.show', $voucher->id)->with('success', 'Voucher created successfully.');
    }

    public function show(int $id)
    {
        $voucher = Journal::with(['branch', 'entries.account'])
            ->whereIn('reference_type', ['receive', 'payment', 'jv'])
            ->findOrFail($id);

        return view('vouchers.show', compact('voucher'));
    }

    public function print(int $id)
    {
        $voucher = Journal::with(['branch', 'entries.account'])
            ->whereIn('reference_type', ['receive', 'payment', 'jv'])
            ->findOrFail($id);

        return view('vouchers.print', compact('voucher'));
    }
}
