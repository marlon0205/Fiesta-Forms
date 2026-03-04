@props(['surveys'])

<div class="p-6 overflow-x-auto">
    @if($surveys->isEmpty())
        <div class="text-center py-12">
            <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600">description</span>
            <p class="mt-4 text-slate-500 dark:text-slate-400 font-medium">No surveys found</p>
            <p class="text-sm text-slate-400 dark:text-slate-500">Create your first survey to get started</p>
        </div>
    @else
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-slate-200 dark:border-slate-700">
                    <th class="py-4 pl-4">Title</th>
                    <th class="py-4">Status</th>
                    <th class="py-4">Performance</th>
                    <th class="py-4 pr-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-slate-700 dark:text-slate-300">
                @foreach($surveys as $survey)
                <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-700/30 group transition-colors">
                    <td class="py-4 pl-4">
                        <div class="font-bold text-slate-900 dark:text-white">{{ $survey->title }}</div>
                        <div class="text-xs text-slate-400">{{ $survey->created_at->format('Y-m-d') }}</div>
                        @if($survey->description)
                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ Str::limit($survey->description, 50) }}</div>
                        @endif
                    </td>
                    <td class="py-4">
                        @if($survey->is_active)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                inactive
                            </span>
                        @endif
                    </td>
                    <td class="py-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold">{{ $survey->votes_count ?? 0 }} votes</span>
                            <div class="w-24 bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-indigo-500 h-full rounded-full" style="width: {{ min(($survey->votes_count ?? 0) / 10, 100) }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 pr-4 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-50 group-hover:opacity-100 transition-opacity">
                            <button onclick="showToast('Edit feature coming soon')" class="w-8 h-8 rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-400 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </button>
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
                @endforeach
            </tbody>
        </table>
    @endif
</div>

