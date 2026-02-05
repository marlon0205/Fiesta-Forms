<header class="h-20 flex-none z-30 transition-all duration-300 px-4 md:px-8 flex items-center justify-between">
    <!-- Logo -->
    <div class="flex items-center gap-3 cursor-pointer group" onclick="window.location.href='{{ route('dashboard.home') }}'">
        <div class="relative w-11 h-11">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-pink-500 rounded-xl blur opacity-75 group-hover:opacity-100 transition duration-200"></div>
            <div class="relative h-11 w-11 rounded-xl bg-white dark:bg-slate-800 p-1 flex items-center justify-center">
                <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
        <div class="hidden md:block">
            <h1 class="text-2xl font-black tracking-tight text-gradient">Fiesta-Forms</h1>
            <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em]">Ford</p>
        </div>
    </div>

    <!-- Floating Nav -->
    <nav class="hidden md:flex gap-2 glass px-2 py-1.5 rounded-full shadow-lg dark:shadow-slate-900/50" id="main-nav">
        <a href="{{ route('dashboard.home') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition-all duration-300 {{ request()->routeIs('dashboard.home') ? 'bg-white dark:bg-slate-800 shadow-md text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
            <span class="material-symbols-outlined text-[20px]">dashboard</span>
            Dashboard
        </a>

        <a href="{{ route('dashboard.explore') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition-all duration-300 {{ request()->routeIs('dashboard.explore') ? 'bg-white dark:bg-slate-800 shadow-md text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
            <span class="material-symbols-outlined text-[20px]">explore</span>
            Explore
        </a>

        @role('admin')
        <a href="{{ route('dashboard.admin') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition-all duration-300 {{ request()->routeIs('dashboard.admin') ? 'bg-white dark:bg-slate-800 shadow-md text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
            <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
            Admin
        </a>
        @endrole
    </nav>

    <!-- User / Controls -->
    <div class="flex items-center gap-3">
        <!-- Theme Toggle -->
        <button onclick="toggleTheme()" class="glass w-10 h-10 rounded-full flex items-center justify-center text-slate-600 dark:text-slate-300 hover:scale-110 transition-transform active:scale-95 shadow-sm">
            <span class="material-symbols-outlined dark:hidden">dark_mode</span>
            <span class="material-symbols-outlined hidden dark:block text-yellow-400">light_mode</span>
        </button>

        @auth

        <div class="flex items-center gap-3 pl-2" id="user-area">
            <div class="text-right hidden sm:block mr-2">
                <div class="text-sm font-bold text-slate-800 dark:text-white leading-tight">{{ auth()->user()->name }}</div>
                <div class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider">
                    @role('admin')
                        ADMIN
                    @endrole
                    @role('customer')
                        CUSTOMER
                    @endrole
                </div>
            </div>
            <a href="{{ route('dashboard.profile') }}" class="relative cursor-pointer group">
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500 to-pink-500 rounded-full blur opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative h-10 w-10 rounded-full border-2 border-white dark:border-slate-700 shadow-md bg-indigo-600 text-white flex items-center justify-center font-bold">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
            </a>
        </div>
        @else
        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-indigo-600 transition-colors">Log In</a>
        <a href="{{ route('register') }}" class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-5 py-2 rounded-full text-sm font-bold hover:shadow-lg hover:scale-105 transition-all">Register</a>
        @endauth
    </div>
</header>

