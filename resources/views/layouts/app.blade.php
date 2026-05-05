<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | Accounting MQ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    @include('components.sidebar')

    <div class="flex-1 flex flex-col">

        <!-- Navbar -->
        @include('components.navbar')

        <!-- Main Content -->
        <main class="p-6">
            <div class="mb-4 flex items-center justify-between bg-white border rounded-lg px-4 py-2">
                <button type="button" onclick="history.back()" title="Go to previous page" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Previous</span>
                </button>
                <nav aria-label="Breadcrumb" class="text-sm text-gray-500">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('dashboard') }}" class="hover:text-gray-700">Home</a></li>
                        @foreach(request()->segments() as $segment)
                            <li>/</li>
                            <li class="text-gray-700">{{ ucfirst(str_replace('-', ' ', $segment)) }}</li>
                        @endforeach
                    </ol>
                </nav>
            </div>
            @yield('content')
        </main>

    </div>

</div>

<!-- Tailwind CDN -->
<script src="{{ asset('public/vendor/js/tailwind.3.4.17.js') }}"></script>

<!-- Alpine.js -->
<script defer src="{{ asset('public/vendor/js/alpinejs.cdn.min.js') }}"></script>

<!-- Chart.js -->
<script src="{{ asset('public/vendor/js/chart.js') }}"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#0f172a',
                    secondary: '#1e293b',
                    accent: '#38bdf8'
                }
            }
        }
    }
</script>

</body>
</html>
