@props(['form'])

<div onclick="window.location.href='{{ route('dashboard.form-detail', $form['id']) }}'" class="flex items-center p-4 rounded-2xl hover:bg-white/50 dark:hover:bg-slate-700/50 cursor-pointer transition-all border border-transparent hover:border-indigo-100 dark:hover:border-slate-600 group">
    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center mr-4 shrink-0 font-black text-lg shadow-sm group-hover:scale-110 transition-transform">
        {{ substr($form['title'], 0, 1) }}
    </div>
    <div class="flex-1 min-w-0">
        <h4 class="font-bold text-slate-800 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $form['title'] }}</h4>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 truncate">{{ $form['category'] }}</p>
    </div>
    <span class="material-symbols-outlined text-slate-300 group-hover:text-indigo-500 transition-colors">arrow_forward_ios</span>
</div>

