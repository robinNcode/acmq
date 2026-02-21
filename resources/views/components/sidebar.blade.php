<div class="w-64 bg-primary text-white hidden md:block">
    <div class="p-6 text-xl font-bold border-b border-gray-700">
        Accounting MQ
    </div>

    <nav class="p-4 space-y-2 text-sm" x-data="{ openReports: false }">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="block p-2 rounded hover:bg-secondary transition">
            Dashboard
        </a>

        <!-- Branches -->
        <a href="{{ route('branches.index') }}"
           class="block p-2 rounded hover:bg-secondary transition">
            Branches
        </a>

        <!-- Purchases -->
        <a href="{{ route('purchases.index') }}"
           class="block p-2 rounded hover:bg-secondary transition">
            Purchases
        </a>

        <!-- Sales -->
        <a href="{{ route('sales.index') }}"
           class="block p-2 rounded hover:bg-secondary transition">
            Sales
        </a>

        <!-- Expenses -->
        <a href="{{ route('expenses.index') }}"
           class="block p-2 rounded hover:bg-secondary transition">
            Expenses
        </a>

        <!-- Reports (Nested Menu) -->
        <div>
            <button
                @click="openReports = !openReports"
                class="w-full flex justify-between items-center p-2 rounded hover:bg-secondary transition focus:outline-none">

                <span>Reports</span>
                <svg :class="{'rotate-180': openReports}"
                     class="w-4 h-4 transform transition-transform duration-200"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Nested Items -->
            <div x-show="openReports" x-transition class="ml-4 mt-2 space-y-1">

                <a href="{{ route('reports.ledger-heads') }}"
                   class="block p-2 rounded hover:bg-secondary transition">
                    - Ledger Heads
                </a>

                <a href="{{ route('reports.balance-sheet') }}"
                   class="block p-2 rounded hover:bg-secondary transition">
                    - Balance Sheet
                </a>

                <a href="{{ route('reports.ledger-entries') }}"
                   class="block p-2 rounded hover:bg-secondary transition">
                    - Ledger Entries
                </a>

            </div>
        </div>

    </nav>
</div>
