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

                    {{-- Strength bar --}}
                    <div class="flex gap-1 mt-2" id="strength-bars">
                        <div class="h-1 flex-1 rounded-full bg-slate-200 dark:bg-slate-700 transition-colors duration-300" id="bar-1"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 dark:bg-slate-700 transition-colors duration-300" id="bar-2"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 dark:bg-slate-700 transition-colors duration-300" id="bar-3"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 dark:bg-slate-700 transition-colors duration-300" id="bar-4"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 dark:bg-slate-700 transition-colors duration-300" id="bar-5"></div>
                    </div>
                    <p class="text-xs font-bold mt-1 hidden" id="strength-label"></p>

                    {{-- Criteria checklist --}}
                    <div class="mt-3 space-y-1.5 hidden" id="pw-criteria">
                        <p class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Password must contain:</p>
                        <div class="grid grid-cols-1 gap-1">
                            <div class="flex items-center gap-2" id="crit-length">
                                <span class="text-slate-300 dark:text-slate-600 text-xs" id="icon-length">○</span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400" id="text-length">At least 12 characters</span>
                            </div>
                            <div class="flex items-center gap-2" id="crit-upper">
                                <span class="text-slate-300 dark:text-slate-600 text-xs" id="icon-upper">○</span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400" id="text-upper">Uppercase letter (A–Z)</span>
                            </div>
                            <div class="flex items-center gap-2" id="crit-lower">
                                <span class="text-slate-300 dark:text-slate-600 text-xs" id="icon-lower">○</span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400" id="text-lower">Lowercase letter (a–z)</span>
                            </div>
                            <div class="flex items-center gap-2" id="crit-number">
                                <span class="text-slate-300 dark:text-slate-600 text-xs" id="icon-number">○</span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400" id="text-number">Number (0–9)</span>
                            </div>
                            <div class="flex items-center gap-2" id="crit-symbol">
                                <span class="text-slate-300 dark:text-slate-600 text-xs" id="icon-symbol">○</span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400" id="text-symbol">Special character (!@#$…)</span>
                            </div>
                        </div>
                    </div>

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

    <script>
        const pwInput = document.getElementById('password');
        const criteria = document.getElementById('pw-criteria');
        const strengthLabel = document.getElementById('strength-label');
        const bars = [1,2,3,4,5].map(i => document.getElementById('bar-' + i));

        const checks = {
            length: { el: 'icon-length', text: 'text-length', test: v => v.length >= 12 },
            upper:  { el: 'icon-upper',  text: 'text-upper',  test: v => /[A-Z]/.test(v) },
            lower:  { el: 'icon-lower',  text: 'text-lower',  test: v => /[a-z]/.test(v) },
            number: { el: 'icon-number', text: 'text-number', test: v => /[0-9]/.test(v) },
            symbol: { el: 'icon-symbol', text: 'text-symbol', test: v => /[^A-Za-z0-9]/.test(v) },
        };

        const strengthConfig = [
            { label: 'Very weak', color: 'bg-red-500',    textClass: 'text-red-500',    bars: 1 },
            { label: 'Weak',      color: 'bg-orange-500', textClass: 'text-orange-500', bars: 2 },
            { label: 'Fair',      color: 'bg-yellow-500', textClass: 'text-yellow-500', bars: 3 },
            { label: 'Strong',    color: 'bg-emerald-500',textClass: 'text-emerald-500',bars: 4 },
            { label: 'Very strong', color: 'bg-green-500',textClass: 'text-green-500', bars: 5 },
        ];

        pwInput.addEventListener('input', () => {
            const val = pwInput.value;

            if (val.length === 0) {
                criteria.classList.add('hidden');
                strengthLabel.classList.add('hidden');
                bars.forEach(b => b.className = 'h-1 flex-1 rounded-full bg-slate-200 dark:bg-slate-700 transition-colors duration-300');
                return;
            }

            criteria.classList.remove('hidden');

            let passed = 0;
            Object.values(checks).forEach(({ el, text, test }) => {
                const ok = test(val);
                if (ok) passed++;
                const icon = document.getElementById(el);
                const label = document.getElementById(text);
                icon.textContent = ok ? '✓' : '○';
                icon.className = ok
                    ? 'text-emerald-500 text-xs font-black'
                    : 'text-slate-300 dark:text-slate-600 text-xs';
                label.className = ok
                    ? 'text-xs font-semibold text-emerald-600 dark:text-emerald-400'
                    : 'text-xs font-semibold text-slate-500 dark:text-slate-400';
            });

            const cfg = strengthConfig[passed - 1] ?? strengthConfig[0];
            bars.forEach((b, i) => {
                b.className = `h-1 flex-1 rounded-full transition-colors duration-300 ${i < cfg.bars ? cfg.color : 'bg-slate-200 dark:bg-slate-700'}`;
            });

            strengthLabel.classList.remove('hidden');
            strengthLabel.textContent = cfg.label;
            strengthLabel.className = `text-xs font-bold mt-1 ${cfg.textClass}`;
        });
    </script>
</x-guest-layout>
