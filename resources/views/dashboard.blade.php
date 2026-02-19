@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Accounting Overview')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <div class="bg-white p-5 rounded-xl shadow">
        <h3 class="text-sm text-gray-500">Total Sales</h3>
        <p class="text-2xl font-bold text-green-600 mt-2">৳ 450,000</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow">
        <h3 class="text-sm text-gray-500">Total Expenses</h3>
        <p class="text-2xl font-bold text-red-600 mt-2">৳ 120,000</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow">
        <h3 class="text-sm text-gray-500">Total Purchases</h3>
        <p class="text-2xl font-bold text-blue-600 mt-2">৳ 300,000</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow">
        <h3 class="text-sm text-gray-500">Net Profit</h3>
        <p class="text-2xl font-bold text-purple-600 mt-2">৳ 30,000</p>
    </div>

</div>

@endsection
