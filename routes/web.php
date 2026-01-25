<?php

use App\Http\Controllers\MetricsController;
use Illuminate\Support\Facades\Route;
use Monolog\Processor\MercurialProcessor;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/metrics', [MetricsController::class, 'index']);
