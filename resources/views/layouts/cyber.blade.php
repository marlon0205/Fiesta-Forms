<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-colors duration-500">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'InsightFlow') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>

    <!-- Initialize theme before page loads to prevent flash -->
    <script>
        // Check for saved theme preference or default to dark mode
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @stack('styles')
</head>
<body class="text-slate-800 dark:text-slate-100 bg-slate-100 dark:bg-slate-900 h-screen flex flex-col overflow-hidden transition-colors duration-500 relative selection:bg-pink-500 selection:text-white">

    <!-- Ambient Background Blobs -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-300 dark:bg-indigo-900 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-50 dark:opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-yellow-300 dark:bg-purple-900 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-50 dark:opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-pink-300 dark:bg-fuchsia-900 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl opacity-50 dark:opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Header -->
    @include('components.cyber.header')

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto relative p-4 md:p-6" id="main-content">
        @yield('content')
    </main>

    <!-- Modal Container -->
    <div class="fixed inset-0 bg-slate-900/20 dark:bg-black/60 backdrop-blur-[8px] z-50 flex items-center justify-center p-4 transition-all duration-300 hidden" id="modal-overlay">
        <div class="glass-panel dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all scale-100 ring-1 ring-white/20" id="modal-content">
            <!-- Modal content will be injected here -->
        </div>
    </div>

    <!-- Notification Toast -->
    <div class="fixed bottom-8 right-8 transition-all duration-500 z-50 glass dark:bg-slate-800 text-slate-800 dark:text-white px-6 py-4 rounded-2xl shadow-2xl border-l-4 border-pink-500 flex items-center gap-4 translate-y-24 opacity-0" id="toast">
        <div class="bg-gradient-to-br from-pink-500 to-violet-600 p-2 rounded-full text-white shadow-lg">
            <span class="material-symbols-outlined text-lg">check</span>
        </div>
        <span id="toast-msg" class="font-medium">Action completed</span>
    </div>

    @stack('scripts')

    <script>
        // Theme toggle functionality
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

        // Toast notification
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-msg');
            toastMsg.innerText = message;
            toast.classList.remove('translate-y-24', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-24', 'opacity-0');
            }, 3000);
        }

        // Modal functions
        function showModal(content) {
            const overlay = document.getElementById('modal-overlay');
            const modalContent = document.getElementById('modal-content');
            modalContent.innerHTML = content;
            overlay.classList.remove('hidden');
        }

        function hideModal() {
            document.getElementById('modal-overlay').classList.add('hidden');
        }

        // Close modal on overlay click
        document.getElementById('modal-overlay')?.addEventListener('click', function(e) {
            if (e.target === this) {
                hideModal();
            }
        });

        // Page transition animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation to main content on page load
            const mainContent = document.getElementById('main-content');
            if (mainContent) {
                mainContent.style.opacity = '0';
                mainContent.style.transform = 'translateY(20px)';

                requestAnimationFrame(() => {
                    mainContent.style.transition = 'opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1), transform 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
                    mainContent.style.opacity = '1';
                    mainContent.style.transform = 'translateY(0)';
                });
            }

            // Smooth page transitions for internal links
            const internalLinks = document.querySelectorAll('a[href^="/dashboard"]');

            internalLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');

                    // Only animate if it's a different page
                    if (href && href !== window.location.pathname && !this.hasAttribute('target')) {
                        e.preventDefault();

                        // Fade out
                        mainContent.style.transition = 'opacity 0.2s ease-out, transform 0.2s ease-out';
                        mainContent.style.opacity = '0';
                        mainContent.style.transform = 'translateX(-20px)';

                        // Navigate after animation
                        setTimeout(() => {
                            window.location.href = href;
                        }, 200);
                    }
                });
            });
        });
    </script>
</body>
</html>

