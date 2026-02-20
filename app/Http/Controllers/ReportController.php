<?php namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller{

    public Sale $sale;
    public function __construct()
    {
        $sale = new Sale();
    }

    public function expenseIndex(){
        $expenses = DB::table('expenses')->get();
        //dd($expenses);
        return view('reports.expense_report', ['expenses' => $expenses]);
    }

    public function salesIndex(){
        $sales = Sale::getSaleReport(null);
        dd($sales);
        return view('reports.sales_report', ['sales' => $sales]);
    }
}
