<x-guest-layout>
    <div class="w-full sm:max-w-md mx-auto glass-panel dark:bg-slate-800/90 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/5 dark:ring-white/10 relative">

        {{-- Gradient accent bar --}}
        <div class="h-1.5 bg-gradient-to-r from-cyan-400 via-indigo-500 to-pink-500"></div>

        <div class="px-7 py-8 sm:px-10">

            {{-- Title --}}
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase">Sign <span class="text-gradient">in</span></h1>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-widest">Welcome back</p>
            </div>

            {{-- Session status --}}
            @if (session('status'))
                <div class="mb-6 flex items-center gap-3 p-3.5 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                    <span class="material-symbols-outlined text-emerald-500 text-xl flex-none">check_circle</span>
                    <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email Address --}}
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           class="w-full bg-white/60 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500/20 text-slate-800 dark:text-white rounded-xl px-4 py-3 text-sm font-medium focus:outline-none transition-colors placeholder-slate-400 dark:placeholder-slate-500 shadow-sm">
                    @error('email')
                        <p class="text-xs text-pink-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-pink-500 transition-colors uppercase tracking-wider">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password"
                           required autocomplete="current-password"
                           class="w-full bg-white/60 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500/20 text-slate-800 dark:text-white rounded-xl px-4 py-3 text-sm font-medium focus:outline-none transition-colors placeholder-slate-400 dark:placeholder-slate-500 shadow-sm">
                    @error('password')
                        <p class="text-xs text-pink-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="pt-2">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                        <input id="remember_me" type="checkbox" name="remember"
                               class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 bg-white/50 dark:bg-slate-900/50">
                        <span class="ml-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest">Remember me</span>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="pt-3">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:via-purple-500 hover:to-pink-500 text-white font-black py-3.5 px-6 rounded-xl shadow-lg shadow-indigo-500/20 transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] text-sm tracking-[0.1em] uppercase">
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center pt-6 border-t border-slate-200 dark:border-slate-700/50">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-pink-500 transition-colors ml-1">Register →</a>
                </p>
            </div>

        </div>
    </div>
</x-guest-layout>
