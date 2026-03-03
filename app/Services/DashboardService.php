<?php
namespace App\Services;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;

class DashboardService
{
    public function getSummaryMetrics()
    {
        $totalSales = Sale::sum('total_price') ?? 0;
        $totalPurchases = Purchase::sum('total_price') ?? 0;
        $totalExpenses = Expense::sum('amount') ?? 0;
        $netProfit = $totalSales - ($totalPurchases + $totalExpenses);

        return [
            'totalSales' => $totalSales,
            'totalPurchases' => $totalPurchases,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
        ];
    }

    public function getChartAnalytics()
    {
        $months = [];
        $salesData = [];
        $purchasesData = [];
        $expensesData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $salesData[] = Sale::whereYear('selling_date', $date->year)
                ->whereMonth('selling_date', $date->month)
                ->sum('total_price');
                
            $purchasesData[] = Purchase::whereYear('purchase_date', $date->year)
                ->whereMonth('purchase_date', $date->month)
                ->sum('total_price');
                
            $expensesData[] = Expense::whereYear('date', $date->year)
                ->whereMonth('date', $date->month)
                ->sum('amount');
        }

        return [
            'months' => $months,
            'salesData' => $salesData,
            'purchasesData' => $purchasesData,
            'expensesData' => $expensesData,
        ];
    }
}
