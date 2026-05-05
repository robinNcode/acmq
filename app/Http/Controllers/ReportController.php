<?php namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Journal;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller{

    public $sale;
    public function __construct()
    {
        $this->sale = new Sale();
    }

    public function index()
    {
        return view('reports.index');
    }

    public function expenseIndex(){
        $expenses = DB::table('expenses')->paginate(30);
        return view('reports.expense_report', ['expenses' => $expenses]);
    }

    public function purchaseIndex()
    {
        $purchases = PurchaseController::getPurchaseReport();
    }

    public function salesIndex(){

    }

    public function ledgerEntries(Request $request)
    {
        $query = JournalEntry::with(['journal', 'account'])->orderBy('id', 'desc');

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->filled('start_date')) {
            $query->whereHas('journal', function($q) use ($request) {
                $q->where('date', '>=', $request->start_date);
            });
        }

        if ($request->filled('end_date')) {
            $query->whereHas('journal', function($q) use ($request) {
                $q->where('date', '<=', $request->end_date);
            });
        }

        $entries = $query->paginate(50);
        $accounts = Account::orderBy('name')->get();

        return view('reports.ledger-entries', compact('entries', 'accounts'));
    }

    public function ledgerEntriesPrint(Request $request)
    {
        $query = JournalEntry::with(['journal', 'account'])->orderBy('id', 'desc');

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->filled('start_date')) {
            $query->whereHas('journal', function ($q) use ($request) {
                $q->where('date', '>=', $request->start_date);
            });
        }

        if ($request->filled('end_date')) {
            $query->whereHas('journal', function ($q) use ($request) {
                $q->where('date', '<=', $request->end_date);
            });
        }

        $entries = $query->get();
        return view('reports.print.ledger-entries', compact('entries'));
    }

    public function journal(Request $request)
    {
        $query = Journal::with(['branch', 'entries.account'])->orderByDesc('date')->orderByDesc('id');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('reference_type')) {
            $query->where('reference_type', $request->reference_type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $journals = $query->paginate(20)->withQueryString();
        $branches = Branch::orderBy('name')->get(['id', 'name']);
        $referenceTypes = Journal::query()
            ->whereNotNull('reference_type')
            ->distinct()
            ->orderBy('reference_type')
            ->pluck('reference_type');

        return view('reports.journal', compact('journals', 'branches', 'referenceTypes'));
    }

    public function printJournal(int $journal)
    {
        $journal = Journal::with(['branch', 'entries.account'])->findOrFail($journal);
        return view('reports.print.journal-card', compact('journal'));
    }

    public function trialBalance(Request $request)
    {
        $accounts = Account::withSum('journalEntries as total_debit', 'debit')
                           ->withSum('journalEntries as total_credit', 'credit')
                           ->get();

        return view('reports.trial-balance', compact('accounts'));
    }

    public function trialBalancePrint(Request $request)
    {
        $accounts = Account::withSum('journalEntries as total_debit', 'debit')
            ->withSum('journalEntries as total_credit', 'credit')
            ->get();

        return view('reports.print.trial-balance', compact('accounts'));
    }

    public function incomeStatement(Request $request)
    {
        $incomeAccounts = Account::where('type', 'income')
            ->withSum('journalEntries as total_credit', 'credit')
            ->withSum('journalEntries as total_debit', 'debit')
            ->get();
            
        $expenseAccounts = Account::where('type', 'expense')
            ->withSum('journalEntries as total_debit', 'debit')
            ->withSum('journalEntries as total_credit', 'credit')
            ->get();
            
        return view('reports.income-statement', compact('incomeAccounts', 'expenseAccounts'));
    }

    public function incomeStatementPrint(Request $request)
    {
        $incomeAccounts = Account::where('type', 'income')
            ->withSum('journalEntries as total_credit', 'credit')
            ->withSum('journalEntries as total_debit', 'debit')
            ->get();

        $expenseAccounts = Account::where('type', 'expense')
            ->withSum('journalEntries as total_debit', 'debit')
            ->withSum('journalEntries as total_credit', 'credit')
            ->get();

        return view('reports.print.income-statement', compact('incomeAccounts', 'expenseAccounts'));
    }

    public function balanceSheet(Request $request)
    {
        $assetAccounts = Account::where('type', 'asset')
            ->withSum('journalEntries as total_debit', 'debit')
            ->withSum('journalEntries as total_credit', 'credit')
            ->get();

        $liabilityAccounts = Account::where('type', 'liability')
            ->withSum('journalEntries as total_credit', 'credit')
            ->withSum('journalEntries as total_debit', 'debit')
            ->get();

        $equityAccounts = Account::where('type', 'equity')
            ->withSum('journalEntries as total_credit', 'credit')
            ->withSum('journalEntries as total_debit', 'debit')
            ->get();
            
        // Calculate Net Income to add to Equity. Getting all income/expense accounts entries sum.
        $totalIncome = Account::where('type', 'income')->get()->sum(function($acc) {
            return $acc->journalEntries()->sum('credit') - $acc->journalEntries()->sum('debit');
        });
        
        $totalExpense = Account::where('type', 'expense')->get()->sum(function($acc) {
            return $acc->journalEntries()->sum('debit') - $acc->journalEntries()->sum('credit');
        });
        
        $netIncome = $totalIncome - $totalExpense;

        return view('reports.balance-sheet', compact('assetAccounts', 'liabilityAccounts', 'equityAccounts', 'netIncome'));
    }

    public function balanceSheetPrint(Request $request)
    {
        $assetAccounts = Account::where('type', 'asset')
            ->withSum('journalEntries as total_debit', 'debit')
            ->withSum('journalEntries as total_credit', 'credit')
            ->get();

        $liabilityAccounts = Account::where('type', 'liability')
            ->withSum('journalEntries as total_credit', 'credit')
            ->withSum('journalEntries as total_debit', 'debit')
            ->get();

        $equityAccounts = Account::where('type', 'equity')
            ->withSum('journalEntries as total_credit', 'credit')
            ->withSum('journalEntries as total_debit', 'debit')
            ->get();

        $totalIncome = Account::where('type', 'income')->get()->sum(function ($acc) {
            return $acc->journalEntries()->sum('credit') - $acc->journalEntries()->sum('debit');
        });

        $totalExpense = Account::where('type', 'expense')->get()->sum(function ($acc) {
            return $acc->journalEntries()->sum('debit') - $acc->journalEntries()->sum('credit');
        });

        $netIncome = $totalIncome - $totalExpense;

        return view('reports.print.balance-sheet', compact('assetAccounts', 'liabilityAccounts', 'equityAccounts', 'netIncome'));
    }
}
