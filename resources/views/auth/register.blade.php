<x-guest-layout>
    <div class="w-full sm:max-w-md mx-auto glass-panel dark:bg-slate-800/90 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/5 dark:ring-white/10 relative">

        {{-- Gradient accent bar --}}
        <div class="h-1.5 bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500"></div>

        <div class="px-7 py-8 sm:px-10">

            {{-- Title --}}
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase">Create <span class="text-gradient">account</span></h1>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-widest">Join Fiesta-Forms</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                           required autofocus autocomplete="name"
                           class="w-full bg-white/60 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 focus:border-pink-500 dark:focus:border-pink-400 focus:ring-2 focus:ring-pink-500/20 text-slate-800 dark:text-white rounded-xl px-4 py-3 text-sm font-medium focus:outline-none transition-colors placeholder-slate-400 dark:placeholder-slate-500 shadow-sm">
                    @error('name')
                        <p class="text-xs text-pink-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           required autocomplete="username"
                           class="w-full bg-white/60 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 focus:border-pink-500 dark:focus:border-pink-400 focus:ring-2 focus:ring-pink-500/20 text-slate-800 dark:text-white rounded-xl px-4 py-3 text-sm font-medium focus:outline-none transition-colors placeholder-slate-400 dark:placeholder-slate-500 shadow-sm">
                    @error('email')
                        <p class="text-xs text-pink-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest">Password</label>
                    <input id="password" type="password" name="password"
                           required autocomplete="new-password"
                           class="w-full bg-white/60 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 focus:border-pink-500 dark:focus:border-pink-400 focus:ring-2 focus:ring-pink-500/20 text-slate-800 dark:text-white rounded-xl px-4 py-3 text-sm font-medium focus:outline-none transition-colors placeholder-slate-400 dark:placeholder-slate-500 shadow-sm">
                    @error('password')
                        <p class="text-xs text-pink-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-xs font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           required autocomplete="new-password"
                           class="w-full bg-white/60 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 focus:border-pink-500 dark:focus:border-pink-400 focus:ring-2 focus:ring-pink-500/20 text-slate-800 dark:text-white rounded-xl px-4 py-3 text-sm font-medium focus:outline-none transition-colors placeholder-slate-400 dark:placeholder-slate-500 shadow-sm">
                    @error('password_confirmation')
                        <p class="text-xs text-pink-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="pt-3">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-pink-600 via-purple-600 to-indigo-600 hover:from-pink-500 hover:via-purple-500 hover:to-indigo-500 text-white font-black py-3.5 px-6 rounded-xl shadow-lg shadow-pink-500/20 transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] text-sm tracking-[0.1em] uppercase">
                        Create account
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center pt-6 border-t border-slate-200 dark:border-slate-700/50">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                    Already registered?
                    <a href="{{ route('login') }}" class="text-pink-600 dark:text-pink-400 hover:text-indigo-500 transition-colors ml-1">Log in →</a>
                </p>
            </div>

        </div>
    </div>
</x-guest-layout>
