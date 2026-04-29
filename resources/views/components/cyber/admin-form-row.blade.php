@props(['survey'])

<tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-700/30 group transition-colors">
    <td class="py-5 pl-4 pr-4 align-top">
        <div class="font-bold text-slate-900 dark:text-white">{{ $survey->title }}</div>
        @if($survey->description)
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ Str::limit($survey->description, 50) }}</div>
        @endif
    </td>
    <td class="py-5 px-4 align-top">
        <div class="font-semibold text-slate-800 dark:text-slate-100">{{ $survey->user?->name ?? 'Unknown owner' }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">
            {{ $survey->serviceCategory?->name ?? $survey->productCategory?->name ?? 'Uncategorized' }}
        </div>
    </td>
    <td class="py-5 px-4 text-center font-semibold tabular-nums text-slate-900 dark:text-slate-100">{{ $survey->questions_count ?? 0 }}</td>
    <td class="py-5 px-4 text-center font-semibold tabular-nums text-slate-900 dark:text-slate-100">{{ $survey->votes_count ?? 0 }}</td>
    <td class="py-5 px-4">
        @if($survey->last_response_at)
            <div class="text-sm text-slate-800 dark:text-slate-100">{{ \Illuminate\Support\Carbon::parse($survey->last_response_at)->diffForHumans() }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400">{{ \Illuminate\Support\Carbon::parse($survey->last_response_at)->format('Y-m-d H:i') }}</div>
        @else
            <span class="text-xs text-slate-400 dark:text-slate-500">No responses yet</span>
        @endif
    </td>
    <td class="py-5 px-4">
        @if($survey->is_active)
            <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                active
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400">
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                inactive
            </span>
        @endif
    </td>
    <td class="py-5 px-4 whitespace-nowrap">
        <div class="text-sm text-slate-800 dark:text-slate-100">{{ $survey->created_at->format('Y-m-d') }}</div>
        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $survey->created_at->format('H:i') }}</div>
    </td>
    <td class="py-5 pl-4 pr-4 text-right align-middle">
        <div class="flex items-center justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
            <a href="{{ route('admin.survey.edit', $survey->survey_id) }}" class="w-8 h-8 rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-400 transition-colors">
                <span class="material-symbols-outlined text-[18px]">edit</span>
            </a>
            <form action="{{ route('dashboard.admin.survey.destroy', $survey->survey_id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this survey? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-8 h-8 rounded-full hover:bg-rose-100 dark:hover:bg-rose-900 flex items-center justify-center text-rose-600 dark:text-rose-400 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </form>
        </div>
    </td>
</tr>
