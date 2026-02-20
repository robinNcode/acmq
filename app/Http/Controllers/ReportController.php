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
}
