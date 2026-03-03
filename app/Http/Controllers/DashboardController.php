<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $metrics = $this->dashboardService->getSummaryMetrics();
        $analytics = $this->dashboardService->getChartAnalytics();

        $totalSales = $metrics['totalSales'];
        $totalPurchases = $metrics['totalPurchases'];
        $totalExpenses = $metrics['totalExpenses'];
        $netProfit = $metrics['netProfit'];

        $months = $analytics['months'];
        $salesData = $analytics['salesData'];
        $purchasesData = $analytics['purchasesData'];
        $expensesData = $analytics['expensesData'];

        return view('dashboard', compact(
            'totalSales',
            'totalPurchases',
            'totalExpenses',
            'netProfit',
            'months',
            'salesData',
            'purchasesData',
            'expensesData'
        ));
    }
}

