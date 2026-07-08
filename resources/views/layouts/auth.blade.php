<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sign In') | Accounting MQ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="{{ asset('public/vendor/js/tailwind.3.4.17.js') }}"></script>
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

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-18px) rotate(3deg); }
        }

        @keyframes floatReverse {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(14px) rotate(-2deg); }
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.35; }
            50% { opacity: 0.7; }
        }

        @keyframes barGrow {
            0%, 100% { transform: scaleY(0.65); }
            50% { transform: scaleY(1); }
        }

        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-reverse { animation: floatReverse 7s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulseGlow 4s ease-in-out infinite; }
        .animate-bar-1 { animation: barGrow 2.4s ease-in-out infinite; transform-origin: bottom; }
        .animate-bar-2 { animation: barGrow 2.4s ease-in-out 0.3s infinite; transform-origin: bottom; }
        .animate-bar-3 { animation: barGrow 2.4s ease-in-out 0.6s infinite; transform-origin: bottom; }
        .animate-bar-4 { animation: barGrow 2.4s ease-in-out 0.9s infinite; transform-origin: bottom; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 antialiased">
    <div class="flex min-h-screen">
        {{-- Brand panel --}}
        <div class="relative hidden lg:flex lg:w-1/2 overflow-hidden bg-gradient-to-br from-primary via-secondary to-slate-900">
            <div class="absolute inset-0 animate-pulse-glow bg-[radial-gradient(circle_at_20%_20%,rgba(56,189,248,0.25),transparent_45%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_80%,rgba(56,189,248,0.12),transparent_40%)]"></div>

            <div class="absolute top-16 left-16 w-24 h-24 rounded-full border border-accent/20 animate-float"></div>
            <div class="absolute bottom-24 right-20 w-32 h-32 rounded-2xl border border-white/10 animate-float-reverse"></div>
            <div class="absolute top-1/3 right-1/4 w-16 h-16 rounded-full bg-accent/10 animate-float-reverse"></div>

            <div class="relative z-10 flex flex-col justify-between p-12 xl:p-16 w-full text-white">
                <div>
                    <div class="flex items-center gap-3 mb-10">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-accent/20 ring-1 ring-accent/30">
                            <svg class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-semibold tracking-tight">Accounting MQ</span>
                    </div>

                    <h1 class="text-4xl xl:text-5xl font-bold leading-tight mb-4">
                        Smart accounting<br>
                        <span class="text-accent">for modern business</span>
                    </h1>
                    <p class="text-slate-300 text-lg max-w-md leading-relaxed">
                        Track sales, purchases, expenses, and financial reports — all in one place.
                    </p>
                </div>

                {{-- Decorative chart motif --}}
                <div class="mt-auto">
                    <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm p-6 max-w-sm">
                        <div class="flex items-end justify-between gap-3 h-28 mb-4 px-2">
                            <div class="w-8 rounded-t-md bg-accent/40 animate-bar-1 h-16"></div>
                            <div class="w-8 rounded-t-md bg-accent/60 animate-bar-2 h-24"></div>
                            <div class="w-8 rounded-t-md bg-accent/80 animate-bar-3 h-20"></div>
                            <div class="w-8 rounded-t-md bg-accent animate-bar-4 h-28"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-sm text-slate-300">
                                <span class="h-2 w-2 rounded-full bg-accent"></span>
                                Real-time financial insights
                            </div>
                            <div class="flex items-center gap-2 text-sm text-slate-300">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                Secure session-based access
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form panel --}}
        <div class="flex flex-1 items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-md">
                <div class="mb-8 text-center lg:hidden">
                    <div class="inline-flex items-center gap-2 text-primary font-semibold text-lg">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-accent/15">
                            <svg class="h-5 w-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        Accounting MQ
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-8 shadow-xl shadow-slate-200/60 ring-1 ring-slate-100">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script defer src="{{ asset('public/vendor/js/alpinejs.cdn.min.js') }}"></script>
</body>
</html>
