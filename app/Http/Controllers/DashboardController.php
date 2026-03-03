<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Calculate general summary metrics
        $totalSales = Sale::sum('total_price') ?? 0;
        $totalPurchases = Purchase::sum('total_price') ?? 0;
        $totalExpenses = Expense::sum('amount') ?? 0;
        $netProfit = $totalSales - ($totalPurchases + $totalExpenses);

        // 2. Prepare last 6 months analytics for charts
        $months = [];
        $salesData = [];
        $purchasesData = [];
        $expensesData = [];

        // Loop over the last 6 months including current
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
