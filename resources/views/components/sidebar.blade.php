<div class="w-64 bg-primary text-white hidden md:block">
    <div class="p-6 text-xl font-bold border-b border-gray-700">
        Accounting MQ
    </div>

    <nav class="p-4 space-y-3 text-sm">

        <a href="{{ route('dashboard') }}" class="block p-2 rounded hover:bg-secondary">
            Dashboard
        </a>

        <a href="{{ route('reports.expense') }}" class="block p-2 rounded hover:bg-secondary">
            Expense Report
        </a>

        <a href="{{ route('reports.sales') }}" class="block p-2 rounded hover:bg-secondary">
            Sales Report
        </a>

        <a href="{{ route('reports.purchase') }}" class="block p-2 rounded hover:bg-secondary">
            Purchase Report
        </a>

        <a href="{{ route('reports.ledger') }}" class="block p-2 rounded hover:bg-secondary">
            Ledger Entries
        </a>

    </nav>
</div>
