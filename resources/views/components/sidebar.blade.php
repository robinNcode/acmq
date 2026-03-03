<div class="w-64 bg-primary text-white hidden md:block">
    <div class="p-6 text-xl font-bold border-b border-gray-700">
        Accounting MQ
    </div>

    <nav class="p-4 space-y-2 text-sm" x-data="{ openReports: {{ request()->routeIs('reports.*') || request()->routeIs('accounts.*') ? 'true' : 'false' }} }">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="block p-2 rounded transition {{ request()->routeIs('dashboard') ? 'bg-secondary text-white font-semibold' : 'hover:bg-secondary' }}">
            Dashboard
        </a>

        <!-- Branches -->
        <a href="{{ route('branches.index') }}"
           class="block p-2 rounded transition {{ request()->routeIs('branches.*') ? 'bg-secondary text-white font-semibold' : 'hover:bg-secondary' }}">
            Branches
        </a>

        <!-- Products -->
        <a href="{{ route('products.index') }}"
           class="block p-2 rounded transition {{ request()->routeIs('products.*') ? 'bg-secondary text-white font-semibold' : 'hover:bg-secondary' }}">
            Products
        </a>

        <!-- Purchases -->
        <a href="{{ route('purchases.index') }}"
           class="block p-2 rounded transition {{ request()->routeIs('purchases.*') ? 'bg-secondary text-white font-semibold' : 'hover:bg-secondary' }}">
            Purchases
        </a>

        <!-- Sales -->
        <a href="{{ route('sales.index') }}"
           class="block p-2 rounded transition {{ request()->routeIs('sales.*') ? 'bg-secondary text-white font-semibold' : 'hover:bg-secondary' }}">
            Sales
        </a>

        <!-- Expenses -->
        <a href="{{ route('expenses.index') }}"
           class="block p-2 rounded transition {{ request()->routeIs('expenses.*') ? 'bg-secondary text-white font-semibold' : 'hover:bg-secondary' }}">
            Expenses
        </a>

        <!-- Reports (Nested Menu) -->
        <div>
            <button
                @click="openReports = !openReports"
                class="w-full flex justify-between items-center p-2 rounded transition focus:outline-none {{ request()->routeIs('reports.*') || request()->routeIs('accounts.*') ? 'bg-secondary text-white font-semibold' : 'hover:bg-secondary' }}">

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

                <a href="{{ route('accounts.index') }}"
                   class="block p-2 rounded transition border-l-2 {{ request()->routeIs('accounts.*') ? 'border-accent bg-secondary/50 font-semibold' : 'border-transparent hover:bg-secondary hover:border-accent' }}">
                    Ledger Heads
                </a>

                <a href="{{ route('reports.ledger-entries') }}"
                   class="block p-2 rounded transition border-l-2 {{ request()->routeIs('reports.ledger-entries') ? 'border-accent bg-secondary/50 font-semibold' : 'border-transparent hover:bg-secondary hover:border-accent' }}">
                    General Ledger
                </a>

                <a href="{{ route('reports.trial-balance') }}"
                   class="block p-2 rounded transition border-l-2 {{ request()->routeIs('reports.trial-balance') ? 'border-accent bg-secondary/50 font-semibold' : 'border-transparent hover:bg-secondary hover:border-accent' }}">
                    Trial Balance
                </a>

                <a href="{{ route('reports.income-statement') }}"
                   class="block p-2 rounded transition border-l-2 {{ request()->routeIs('reports.income-statement') ? 'border-accent bg-secondary/50 font-semibold' : 'border-transparent hover:bg-secondary hover:border-accent' }}">
                    Income Statement
                </a>

                <a href="{{ route('reports.balance-sheet') }}"
                   class="block p-2 rounded transition border-l-2 {{ request()->routeIs('reports.balance-sheet') ? 'border-accent bg-secondary/50 font-semibold' : 'border-transparent hover:bg-secondary hover:border-accent' }}">
                    Balance Sheet
                </a>

            </div>
        </div>

    </nav>
</div>

