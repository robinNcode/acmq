<header class="bg-white shadow-sm">
    <div class="flex justify-between items-center px-6 py-4">

        <h1 class="text-lg font-semibold text-gray-700">
            @yield('page-title')
        </h1>

        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-accent text-xs font-semibold text-primary">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="text-sm text-gray-500 hover:text-red-600 transition"
                    title="Sign out"
                >
                    Sign out
                </button>
            </form>
        </div>

    </div>
</header>
