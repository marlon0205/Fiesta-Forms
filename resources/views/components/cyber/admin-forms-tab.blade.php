@props(['surveys'])

<div class="p-6 overflow-x-auto">
    @if($surveys->count() === 0)
        <div class="text-center py-12">
            <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600">description</span>
            <p class="mt-4 text-slate-500 dark:text-slate-400 font-medium">No surveys found</p>
            <p class="text-sm text-slate-400 dark:text-slate-500">Create your first survey to get started</p>
        </div>
    @else
        @php
            $currentSort = request('sort', 'created');
            $currentDirection = request('direction', 'desc') === 'asc' ? 'asc' : 'desc';

            $sortUrl = function ($column, $currentSort, $currentDirection) {
                $direction = $currentSort === $column && $currentDirection === 'asc' ? 'desc' : 'asc';

                return request()->fullUrlWithQuery([
                    'sort' => $column,
                    'direction' => $direction,
                    'page' => 1,
                ]);
            };

            $sortIcon = function ($column, $currentSort, $currentDirection) {
                if ($currentSort !== $column) {
                    return 'unfold_more';
                }

                return $currentDirection === 'asc' ? 'arrow_upward' : 'arrow_downward';
            };
        @endphp

        <table class="w-full min-w-[1100px] border-collapse text-left align-middle">
            <thead>
                <tr class="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-slate-200 dark:border-slate-700">
                    <th class="py-3 pl-4 pr-4">
                        <a href="{{ $sortUrl('title', $currentSort, $currentDirection) }}" class="inline-flex items-center gap-1 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            Form
                            <span class="material-symbols-outlined text-sm leading-none {{ $currentSort === 'title' ? 'opacity-100' : 'opacity-20' }}">{{ $sortIcon('title', $currentSort, $currentDirection) }}</span>
                        </a>
                    </th>
                    <th class="py-3 px-4">Owner / Category</th>
                    <th class="py-3 px-4 text-center">
                        <a href="{{ $sortUrl('questions', $currentSort, $currentDirection) }}" class="inline-flex items-center justify-center gap-1 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            Questions
                            <span class="material-symbols-outlined text-sm leading-none {{ $currentSort === 'questions' ? 'opacity-100' : 'opacity-20' }}">{{ $sortIcon('questions', $currentSort, $currentDirection) }}</span>
                        </a>
                    </th>
                    <th class="py-3 px-4 text-center">
                        <a href="{{ $sortUrl('responses', $currentSort, $currentDirection) }}" class="inline-flex items-center justify-center gap-1 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            Responses
                            <span class="material-symbols-outlined text-sm leading-none {{ $currentSort === 'responses' ? 'opacity-100' : 'opacity-20' }}">{{ $sortIcon('responses', $currentSort, $currentDirection) }}</span>
                        </a>
                    </th>
                    <th class="py-3 px-4">
                        <a href="{{ $sortUrl('last_response', $currentSort, $currentDirection) }}" class="inline-flex items-center gap-1 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            Last Response
                            <span class="material-symbols-outlined text-sm leading-none {{ $currentSort === 'last_response' ? 'opacity-100' : 'opacity-20' }}">{{ $sortIcon('last_response', $currentSort, $currentDirection) }}</span>
                        </a>
                    </th>
                    <th class="py-3 px-4">
                        <a href="{{ $sortUrl('status', $currentSort, $currentDirection) }}" class="inline-flex items-center gap-1 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            Status
                            <span class="material-symbols-outlined text-sm leading-none {{ $currentSort === 'status' ? 'opacity-100' : 'opacity-20' }}">{{ $sortIcon('status', $currentSort, $currentDirection) }}</span>
                        </a>
                    </th>
                    <th class="py-3 px-4">
                        <a href="{{ $sortUrl('created', $currentSort, $currentDirection) }}" class="inline-flex items-center gap-1 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            Created
                            <span class="material-symbols-outlined text-sm leading-none {{ $currentSort === 'created' ? 'opacity-100' : 'opacity-20' }}">{{ $sortIcon('created', $currentSort, $currentDirection) }}</span>
                        </a>
                    </th>
                    <th class="py-3 pl-4 pr-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-slate-700 dark:text-slate-300">
                @foreach($surveys as $survey)
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
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $surveys->onEachSide(1)->links() }}
        </div>
    @endif
</div>
