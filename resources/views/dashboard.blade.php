@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Accounting Overview')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-green-500">
        <h3 class="text-sm text-gray-500 uppercase tracking-wide">Total Sales</h3>
        <p class="text-2xl font-bold text-gray-800 mt-2">৳ {{ number_format($totalSales, 2) }}</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-red-500">
        <h3 class="text-sm text-gray-500 uppercase tracking-wide">Total Expenses</h3>
        <p class="text-2xl font-bold text-gray-800 mt-2">৳ {{ number_format($totalExpenses, 2) }}</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-blue-500">
        <h3 class="text-sm text-gray-500 uppercase tracking-wide">Total Purchases</h3>
        <p class="text-2xl font-bold text-gray-800 mt-2">৳ {{ number_format($totalPurchases, 2) }}</p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow border-l-4 {{ $netProfit >= 0 ? 'border-purple-500' : 'border-red-600' }}">
        <h3 class="text-sm text-gray-500 uppercase tracking-wide">Net Profit</h3>
        <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-purple-600' : 'text-red-600' }} mt-2">
            ৳ {{ number_format($netProfit, 2) }}
        </p>
    </div>
</div>

<div class="bg-white p-6 rounded-xl shadow">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Financial Overview (Last 6 Months)</h3>
    <div class="relative h-96 w-full">
        <canvas id="financialChart"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('financialChart').getContext('2d');
        
        const labels = {!! json_encode($months) !!};
        const salesData = {!! json_encode($salesData) !!};
        const purchasesData = {!! json_encode($purchasesData) !!};
        const expensesData = {!! json_encode($expensesData) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Sales (৳)',
                        data: salesData,
                        backgroundColor: 'rgba(34, 197, 94, 0.7)', // Green-500
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Purchases (৳)',
                        data: purchasesData,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)', // Blue-500
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Expenses (৳)',
                        data: expensesData,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)', // Red-500
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += '৳ ' + new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '৳ ' + value.toLocaleString();
                            }
                        },
                        grid: {
                            borderDash: [5, 5]
                        }
                    }
                }
            }
        });
    });
</script>

@endsection
