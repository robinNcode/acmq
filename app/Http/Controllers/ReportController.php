<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller{

    public function expenseIndex(){
        $expenses = DB::table('expenses')->get();
        //dd($expenses);
        return view('reports.expense_report', ['expenses' => $expenses]);
    }
}