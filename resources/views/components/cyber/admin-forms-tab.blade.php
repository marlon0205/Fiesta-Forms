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
                    <x-cyber.admin-form-row :survey="$survey" />
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $surveys->onEachSide(1)->links() }}
        </div>
    @endif
</div>
