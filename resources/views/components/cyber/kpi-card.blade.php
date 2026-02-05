@props(['label', 'value', 'icon', 'gradient' => 'from-blue-500 to-cyan-400'])

<div class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
    <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br {{ $gradient }} rounded-full opacity-10 blur-xl group-hover:opacity-20 transition-opacity"></div>
    <div class="flex items-center gap-4 relative z-10">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-white shadow-lg">
            <span class="material-symbols-outlined text-2xl">{{ $icon }}</span>
        </div>
        <div>
            <div class="text-2xl font-black text-slate-800 dark:text-white">{{ $value }}</div>
            <div class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $label }}</div>
        </div>
    </div>
</div>

