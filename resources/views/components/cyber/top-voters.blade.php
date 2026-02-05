@props(['voters'])

<div class="glass-card rounded-3xl p-6 md:p-8 bg-gradient-to-b from-white/40 to-white/10 dark:from-slate-800/40 dark:to-slate-800/10">
    <h3 class="font-bold text-xl text-slate-800 dark:text-white mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-yellow-500">trophy</span> Champions
    </h3>
    <div class="space-y-6">
        @foreach($voters as $index => $voter)
        <div class="flex items-center gap-4 group">
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-yellow-400 to-orange-500 rounded-full blur-sm opacity-0 group-hover:opacity-75 transition-opacity"></div>
                <div class="relative w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold border-2 border-white dark:border-slate-700">
                    {{ substr($voter['name'], 0, 2) }}
                </div>
                <div class="absolute -top-2 -right-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-[10px] font-black w-6 h-6 flex items-center justify-center rounded-full shadow-sm">{{ $index + 1 }}</div>
            </div>
            <div>
                <div class="font-bold text-slate-800 dark:text-slate-100">{{ $voter['name'] }}</div>
                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">{{ $voter['votes'] }} votes</div>
            </div>
        </div>
        @endforeach
    </div>
</div>

