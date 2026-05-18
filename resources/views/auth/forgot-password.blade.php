<x-guest-layout>
    <div class="w-full sm:max-w-md mx-auto glass-panel dark:bg-slate-800/90 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/5 dark:ring-white/10 relative">

        {{-- Gradient accent bar --}}
        <div class="h-1.5 bg-gradient-to-r from-yellow-400 via-orange-500 to-red-500"></div>

        <div class="px-7 py-8 sm:px-10">

            {{-- Title --}}
            <div class="mb-8 text-center flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl border border-orange-500/20 bg-orange-50 dark:bg-orange-900/30 flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-orange-500 text-[28px]">lock_reset</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase">Reset <span class="text-gradient" style="background-image: linear-gradient(to right, #facc15, #f97316, #ef4444);">Password</span></h1>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-widest">Forgot your password?</p>
            </div>

            {{-- Session status --}}
            @if (session('status'))
                <div class="mb-6 flex items-center gap-3 p-3.5 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                    <span class="material-symbols-outlined text-emerald-500 text-xl flex-none">mark_email_read</span>
                    <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           required autofocus
                           class="w-full bg-white/60 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 focus:border-orange-500 dark:focus:border-orange-400 focus:ring-2 focus:ring-orange-500/20 text-slate-800 dark:text-white rounded-xl px-4 py-3 text-sm font-medium focus:outline-none transition-colors placeholder-slate-400 dark:placeholder-slate-500 shadow-sm">
                    @error('email')
                        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="pt-3">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 hover:from-yellow-400 hover:via-orange-400 hover:to-red-400 text-white font-black py-3.5 px-6 rounded-xl shadow-lg shadow-orange-500/20 transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] text-sm tracking-[0.1em] uppercase">
                        Send reset link
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center pt-6 border-t border-slate-200 dark:border-slate-700/50">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-orange-500 transition-colors uppercase tracking-widest">
                    <span class="material-symbols-outlined text-[18px]">arrow_left_alt</span>
                    Back to sign in
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>
