<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full transition-colors duration-500">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fiesta-Forms') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="h-full min-h-screen text-slate-800 dark:text-slate-100 bg-slate-100 dark:bg-slate-900 transition-colors duration-500 selection:bg-pink-500 selection:text-white overflow-x-hidden">

    {{-- Ambient background blobs --}}
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-5%] w-[500px] h-[500px] bg-indigo-300 dark:bg-indigo-900 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-40 dark:opacity-25 animate-blob"></div>
        <div class="absolute top-[-5%] right-[-5%] w-[450px] h-[450px] bg-purple-300 dark:bg-violet-900 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-40 dark:opacity-25 animate-blob" style="animation-delay: 2s"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[500px] h-[500px] bg-pink-300 dark:bg-fuchsia-900 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-40 dark:opacity-25 animate-blob" style="animation-delay: 4s"></div>
        <div class="absolute bottom-[10%] right-[10%] w-[350px] h-[350px] bg-yellow-200 dark:bg-purple-900 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-30 dark:opacity-20 animate-blob" style="animation-delay: 1s"></div>
    </div>

    {{-- Theme toggle --}}
    <div class="fixed top-5 right-5 z-50">
        <button onclick="toggleTheme()"
                class="glass w-10 h-10 rounded-full flex items-center justify-center text-slate-600 dark:text-slate-300 hover:scale-110 transition-transform active:scale-95 shadow-md">
            <span class="material-symbols-outlined dark:hidden text-[20px]">dark_mode</span>
            <span class="material-symbols-outlined hidden dark:block text-yellow-400 text-[20px]">light_mode</span>
        </button>
    </div>

    {{-- Centered layout --}}
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">

        {{-- Logo above card --}}
        <a href="{{ route('dashboard.home') }}" class="flex items-center gap-3 mb-8 group">
            <div class="relative w-11 h-11">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-pink-500 rounded-xl blur opacity-75 group-hover:opacity-100 transition duration-200"></div>
                <div class="relative h-11 w-11 rounded-xl bg-white dark:bg-slate-800 p-1.5 flex items-center justify-center shadow-sm">
                    <svg class="w-full h-full text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <span class="text-2xl font-black tracking-tight text-gradient">Fiesta-Forms</span>
        </a>

        {{-- Card slot --}}
        <div class="w-full max-w-sm fade-in">
            {{ $slot }}
        </div>

    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
</body>
</html>
