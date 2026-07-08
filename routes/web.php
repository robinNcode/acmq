<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MetricsController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index']);
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Metrics
|--------------------------------------------------------------------------
*/
Route::get('/metrics', [MetricsController::class, 'index'])
    ->name('metrics.index');

/*
|--------------------------------------------------------------------------
| Core Modules
|--------------------------------------------------------------------------
*/

Route::resource('branches', BranchController::class);
Route::resource('products', ProductController::class);
Route::resource('purchases', PurchaseController::class);
Route::resource('sales', SaleController::class);
Route::resource('expenses', ExpenseController::class);

Route::get('sales/{sale}/invoice', [SaleController::class, 'invoice'])->name('sales.invoice');

Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
Route::put('/accounts/{account}', [AccountController::class, 'update'])->name('accounts.update');
Route::delete('/accounts/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');

/*
|--------------------------------------------------------------------------
| Reports (Nested)
|--------------------------------------------------------------------------
*/

Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/journal/{journal}/print', [ReportController::class, 'printJournal'])
        ->name('journal.print');
    Route::get('/journal', [ReportController::class, 'journal'])
        ->name('journal');

    Route::get('/balance-sheet', [ReportController::class, 'balanceSheet'])
        ->name('balance-sheet');

    Route::get('/ledger-entries', [ReportController::class, 'ledgerEntries'])
        ->name('ledger-entries');
        
    Route::get('/trial-balance', [ReportController::class, 'trialBalance'])
        ->name('trial-balance');
    Route::get('/trial-balance/print', [ReportController::class, 'trialBalancePrint'])
        ->name('trial-balance.print');

    Route::get('/income-statement', [ReportController::class, 'incomeStatement'])->name('income-statement');
    Route::get('/income-statement/print', [ReportController::class, 'incomeStatementPrint'])->name('income-statement.print');
    Route::get('/balance-sheet/print', [ReportController::class, 'balanceSheetPrint'])->name('balance-sheet.print');
    Route::get('/ledger-entries/print', [ReportController::class, 'ledgerEntriesPrint'])->name('ledger-entries.print');
});

Route::prefix('vouchers')->name('vouchers.')->group(function () {
    Route::get('/', [VoucherController::class, 'index'])->name('index');
    Route::get('/create', [VoucherController::class, 'create'])->name('create');
    Route::post('/', [VoucherController::class, 'store'])->name('store');
    Route::get('/{id}', [VoucherController::class, 'show'])->name('show');
    Route::get('/{id}/print', [VoucherController::class, 'print'])->name('print');
});
