@props(['form'])

@php
$isActive = $form['is_active'] === 'active';
$statusColor = $isActive ? 'bg-emerald-500' : 'bg-slate-500';
@endphp

<div onclick="window.location.href='{{ route('dashboard.form-detail', $form['survey_id']) }}'" class="group glass-card rounded-[2rem] overflow-hidden cursor-pointer hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col h-full relative">
    <!-- Image Area -->
    <div class="h-40 bg-slate-200 dark:bg-slate-800 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10"></div>
        <div class="w-full h-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 group-hover:scale-110 transition-transform duration-700"></div>

        <div class="absolute top-4 right-4 z-20">
            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-white backdrop-blur-md bg-black/30 border border-white/20 shadow-sm flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full {{ $statusColor }} {{ $isActive ? 'animate-pulse' : '' }}"></span>
                {{ $form['status'] }}
            </span>
        </div>

        <div class="absolute bottom-4 left-4 z-20">
            <span class="text-[10px] font-bold text-white uppercase tracking-wider bg-indigo-600/80 px-2 py-0.5 rounded backdrop-blur-sm">{{ $form['category'] }}</span>
        </div>
    </div>

    <!-- Content -->
    <div class="p-6 flex-1 flex flex-col bg-white/60 dark:bg-slate-800/40 backdrop-blur-sm">
        <h3 class="font-black text-xl text-slate-900 dark:text-white mb-2 leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $form['title'] }}</h3>
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 line-clamp-2 mb-6 flex-1">{{ $form['description'] }}</p>

        <div class="flex items-center justify-between pt-4 border-t border-slate-200/60 dark:border-slate-700/60 mt-auto">
            <div class="flex items-center text-xs font-bold text-slate-400 gap-1">
                <span class="material-symbols-outlined text-sm">group</span> {{ $form['submissions'] }}
            </div>
            <div class="text-xs font-bold text-indigo-500 dark:text-indigo-400 group-hover:translate-x-1 transition-transform flex items-center">
                Details <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span>
            </div>
        </div>
    </div>
</div>

