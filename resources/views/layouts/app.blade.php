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
            @yield('content')
        </main>

    </div>

</div>

<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>

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
