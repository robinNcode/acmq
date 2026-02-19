<?php

use App\Http\Controllers\MetricsController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Monolog\Processor\MercurialProcessor;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/metrics', [MetricsController::class, 'index']);

// Report Controller Routes Starts here ...............................
Route::get('expense/report', [ReportController::class, 'expenseIndex'])->name('reports.expense');
Route::get('sales/report', [ReportController::class, 'salesIndex'])->name('reports.sales');
Route::get('purchases/report', [ReportController::class, 'purchaseIndex'])->name('reports.purchase');
Route::get('ledger/report', [ReportController::class, 'Index'])->name('reports.ledger');
