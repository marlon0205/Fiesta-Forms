<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'FormEnterprise') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'media', // oder 'class'
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: '#F7D850', // Deine Akzentfarbe aus dem Laravel-Beispiel
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        /* Hide scrollbar for sleek look in tables if needed */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] h-full antialiased selection:bg-primary selection:text-white">

<header class="sticky top-0 z-50 w-full backdrop-blur-md bg-white/80 dark:bg-[#161615]/80 border-b border-gray-200 dark:border-[#3E3E3A]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-3">
                <div class="bg-primary/10 p-2 rounded-lg">
                    <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="font-bold text-lg tracking-tight">FormEnterprise</span>
            </div>

            <div class="hidden md:flex items-center gap-6">
                @auth
                @if(Auth::user()->role === 'Customer')
                <div class="flex items-center gap-3 text-sm font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-[#1f1f1e] px-3 py-1.5 rounded-full">
                    <span class="flex items-center gap-1"><span class="text-primary">★</span> 12 Votes</span>
                    <span class="w-px h-3 bg-gray-300 dark:bg-gray-600"></span>
                    <span class="flex items-center gap-1">🏆 3 Badges</span>
                </div>
                @endif

                <div class="flex items-center gap-3 pl-4 border-l border-gray-200 dark:border-[#3E3E3A]">
                    <div class="text-right hidden lg:block">
                        <div class="text-sm font-semibold leading-none">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ Auth::user()->role ?? 'Admin' }}</div>
                    </div>
                    <div class="h-9 w-9 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-md ring-2 ring-white dark:ring-[#161615]">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="text-sm font-medium hover:text-primary transition-colors">Log in</a>
                <a href="{{ route('register') }}" class="bg-[#1b1b18] dark:bg-white text-white dark:text-black px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-gray-500/20 hover:shadow-gray-500/40 hover:-translate-y-0.5 transition-all duration-300">
                    Get Started
                </a>
                @endauth
            </div>

            <div class="md:hidden flex items-center">
                <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-[#1f1f1e]">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>
        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Dashboard Overview</h1>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Manage your forms, analyze responses, and track performance.</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-[#3E3E3A] text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-[#1f1f1e] transition shadow-sm">
                Archived Forms
            </button>
            <button class="bg-primary text-black px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-primary/30 hover:bg-red-600 hover:-translate-y-0.5 transition-all">
                + Create New Form
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="group bg-white dark:bg-[#161615] p-5 rounded-2xl border border-gray-200 dark:border-[#3E3E3A] shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Forms</p>
                    <h3 class="text-3xl font-bold mt-2 text-gray-900 dark:text-white">248</h3>
                </div>
                <div class="p-2 bg-gray-50 dark:bg-[#1f1f1e] rounded-lg group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
            </div>
        </div>

        <div class="group bg-white dark:bg-[#161615] p-5 rounded-2xl border border-green-100 dark:border-green-900/30 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-green-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-125"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Forms</p>
                    <div class="flex items-baseline gap-2 mt-2">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white">12</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                Live
                            </span>
                    </div>
                </div>
                <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
            </div>
        </div>

        <div class="group bg-white dark:bg-[#161615] p-5 rounded-2xl border border-gray-200 dark:border-[#3E3E3A] shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Registered Customers</p>
                    <h3 class="text-3xl font-bold mt-2 text-gray-900 dark:text-white">1,402</h3>
                </div>
                <div class="p-2 bg-gray-50 dark:bg-[#1f1f1e] rounded-lg group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
            </div>
        </div>

        <div class="group bg-white dark:bg-[#161615] p-5 rounded-2xl border border-gray-200 dark:border-[#3E3E3A] shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Answers Submitted</p>
                    <h3 class="text-3xl font-bold mt-2 text-gray-900 dark:text-white">85.4k</h3>
                </div>
                <div class="p-2 bg-gray-50 dark:bg-[#1f1f1e] rounded-lg group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 flex flex-col gap-6">

            <div class="bg-white dark:bg-[#161615] p-4 rounded-2xl border border-gray-200 dark:border-[#3E3E3A] shadow-sm flex flex-col sm:flex-row gap-4 items-center justify-between">
                <h2 class="font-semibold text-lg ml-2">Recent Forms</h2>
                <div class="flex w-full sm:w-auto gap-2">
                    <div class="relative grow sm:grow-0">
                        <input type="text" placeholder="Search forms..." class="w-full sm:w-64 pl-9 pr-4 py-2 rounded-lg border border-gray-200 dark:border-[#3E3E3A] bg-gray-50 dark:bg-[#1f1f1e] text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div class="relative group w-full sm:w-auto">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-primary transition-colors">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                        </div>

                        <select class="appearance-none w-full bg-gray-50 dark:bg-[#1f1f1e] hover:bg-white dark:hover:bg-[#161615] border border-gray-200 dark:border-[#3E3E3A] text-gray-700 dark:text-gray-200 py-2 pl-10 pr-8 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all cursor-pointer shadow-sm">
                            <option value="" selected>All Categories</option>
                            <option value="hr">HR Services</option>
                            <option value="product">Product</option>
                            <option value="service">Service</option>
                        </select>

                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-gray-400">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                @foreach(['Q3 Employee Satisfaction', 'New Product Launch Feedback', 'Canteen Menu Survey', 'IT Support Ticket Review'] as $index => $form)
                <div class="group bg-white dark:bg-[#161615] p-5 rounded-xl border border-gray-200 dark:border-[#3E3E3A] hover:border-primary/50 dark:hover:border-primary/50 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 cursor-pointer flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div class="flex items-start gap-4">
                        <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-[#1f1f1e] flex items-center justify-center text-lg shrink-0">
                            {{ ['👔', '🚀', '🍔', '💻'][$index] }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-primary transition-colors">{{ $form }}</h3>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                    <span class="bg-gray-100 dark:bg-[#27272a] px-2 py-0.5 rounded text-gray-600 dark:text-gray-400">
                                        {{ ['HR Services', 'Product', 'General', 'Support'][$index] }}
                                    </span>
                                <span>• Created 2 days ago</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-6">
                        <div class="text-right">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">143</div>
                            <div class="text-xs text-gray-500">Answers</div>
                        </div>
                        <div class="hidden sm:block w-24">
                            <div class="flex justify-between text-[10px] text-gray-500 mb-1">
                                <span>Progress</span>
                                <span>75%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-[#27272a] rounded-full h-1.5">
                                <div class="bg-primary h-1.5 rounded-full" style="width: 75%"></div>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </div>
                @endforeach
            </div>

            <button class="w-full py-3 rounded-xl border border-dashed border-gray-300 dark:border-gray-700 text-gray-500 hover:bg-gray-50 dark:hover:bg-[#1f1f1e] hover:border-gray-400 transition text-sm">
                Load more forms...
            </button>
        </div>

        <div class="space-y-6">

            <div class="bg-[#1b1b18] dark:bg-black text-white rounded-2xl shadow-xl overflow-hidden relative border border-gray-800">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/20 blur-3xl rounded-full pointer-events-none"></div>

                <div class="p-6 relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-lg flex items-center gap-2">
                            <span class="text-yellow-400">🏆</span> Top Voters
                        </h3>
                        <span class="text-xs font-medium bg-white/10 px-2 py-1 rounded text-gray-300">This Month</span>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-2 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-6 text-center font-bold text-yellow-400">1</div>
                                <img src="https://i.pravatar.cc/150?u=1" alt="Avatar" class="w-8 h-8 rounded-full ring-2 ring-yellow-400/50">
                                <div class="text-sm font-medium">John Doe</div>
                            </div>
                            <div class="text-xs font-bold text-yellow-400">42 Votes</div>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-white/5 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-6 text-center font-bold text-gray-400">2</div>
                                <img src="https://i.pravatar.cc/150?u=2" alt="Avatar" class="w-8 h-8 rounded-full">
                                <div class="text-sm font-medium text-gray-300">Anna Smith</div>
                            </div>
                            <div class="text-xs font-bold text-gray-400">38 Votes</div>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-white/5 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-6 text-center font-bold text-orange-400">3</div>
                                <img src="https://i.pravatar.cc/150?u=3" alt="Avatar" class="w-8 h-8 rounded-full">
                                <div class="text-sm font-medium text-gray-300">Max Kraft</div>
                            </div>
                            <div class="text-xs font-bold text-orange-400">31 Votes</div>
                        </div>
                    </div>

                    <button class="w-full mt-6 text-xs text-gray-400 hover:text-white transition border-t border-white/10 pt-3">View full leaderboard</button>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] rounded-2xl border border-gray-200 dark:border-[#3E3E3A] p-6 shadow-sm">
                <h3 class="font-semibold text-lg mb-4">Performance Insights</h3>

                <div class="flex gap-4 items-start mb-4">
                    <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Best Performing</p>
                        <p class="text-sm font-bold mt-0.5">IT Support Feedback</p>
                        <p class="text-xs text-gray-500 mt-0.5">1,204 submissions total</p>
                    </div>
                </div>

                <div class="w-full border-t border-gray-100 dark:border-[#27272a] my-4"></div>

                <div class="flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-full bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6 6" /></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Needs Attention</p>
                        <p class="text-sm font-bold mt-0.5">Parking Survey</p>
                        <p class="text-xs text-gray-500 mt-0.5">Only 3 submissions recently</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
</body>
</html>
