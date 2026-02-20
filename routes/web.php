<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MetricsController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

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

Route::get('/purchases', [PurchaseController::class, 'index'])
    ->name('purchases.index');

Route::get('/sales', [SaleController::class, 'index'])
    ->name('sales.index');

Route::get('/expenses', [ExpenseController::class, 'index'])
    ->name('expenses.index');

/*
|--------------------------------------------------------------------------
| Reports (Nested)
|--------------------------------------------------------------------------
*/

Route::prefix('reports')->name('reports.')->group(function () {

    Route::get('/ledger-heads', [ReportController::class, 'ledgerHeads'])
        ->name('ledger-heads');

    Route::get('/balance-sheet', [ReportController::class, 'balanceSheet'])
        ->name('balance-sheet');

    Route::get('/ledger-entries', [ReportController::class, 'ledgerEntries'])
        ->name('ledger-entries');
});
