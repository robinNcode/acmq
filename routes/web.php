<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MetricsController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
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
    Route::get('/journal', [ReportController::class, 'journal'])
        ->name('journal');

    Route::get('/balance-sheet', [ReportController::class, 'balanceSheet'])
        ->name('balance-sheet');

    Route::get('/ledger-entries', [ReportController::class, 'ledgerEntries'])
        ->name('ledger-entries');
        
    Route::get('/trial-balance', [ReportController::class, 'trialBalance'])
        ->name('trial-balance');

    Route::get('/income-statement', [ReportController::class, 'incomeStatement'])
        ->name('income-statement');
});
