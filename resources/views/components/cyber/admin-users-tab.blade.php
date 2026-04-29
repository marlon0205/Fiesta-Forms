@props(['users'])

<div class="p-6 overflow-x-auto">
    @if($users->count() === 0)
        <div class="text-center py-12">
            <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600">group</span>
            <p class="mt-4 text-slate-500 dark:text-slate-400 font-medium">No users found</p>
            <p class="text-sm text-slate-400 dark:text-slate-500">Registered users will appear here</p>
        </div>
    @else
        @php
            $currentSort = request('user_sort', 'created');
            $currentDirection = request('user_direction', 'desc') === 'asc' ? 'asc' : 'desc';

            $sortUrl = function ($column, $currentSort, $currentDirection) {
                $direction = $currentSort === $column && $currentDirection === 'asc' ? 'desc' : 'asc';

                return request()->fullUrlWithQuery([
                    'tab' => 'users',
                    'user_sort' => $column,
                    'user_direction' => $direction,
                    'users_page' => 1,
                ]);
            };

            $sortIcon = function ($column, $currentSort, $currentDirection) {
                if ($currentSort !== $column) {
                    return 'unfold_more';
                }

                return $currentDirection === 'asc' ? 'arrow_upward' : 'arrow_downward';
            };
        @endphp

        <table class="w-full min-w-[1000px] border-collapse text-left align-middle">
            <thead>
                <tr class="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-slate-200 dark:border-slate-700">
                    <th class="py-3 pl-4 pr-4">
                        <a href="{{ $sortUrl('name', $currentSort, $currentDirection) }}" class="inline-flex items-center gap-1 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            Username
                            <span class="material-symbols-outlined text-sm leading-none {{ $currentSort === 'name' ? 'opacity-100' : 'opacity-20' }}">{{ $sortIcon('name', $currentSort, $currentDirection) }}</span>
                        </a>
                    </th>
                    <th class="py-3 px-4">
                        <a href="{{ $sortUrl('email', $currentSort, $currentDirection) }}" class="inline-flex items-center gap-1 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            Email Address
                            <span class="material-symbols-outlined text-sm leading-none {{ $currentSort === 'email' ? 'opacity-100' : 'opacity-20' }}">{{ $sortIcon('email', $currentSort, $currentDirection) }}</span>
                        </a>
                    </th>
                    <th class="py-3 px-4">
                        <a href="{{ $sortUrl('role', $currentSort, $currentDirection) }}" class="inline-flex items-center gap-1 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            Role
                            <span class="material-symbols-outlined text-sm leading-none {{ $currentSort === 'role' ? 'opacity-100' : 'opacity-20' }}">{{ $sortIcon('role', $currentSort, $currentDirection) }}</span>
                        </a>
                    </th>
                    <th class="py-3 px-4 text-center">
                        <a href="{{ $sortUrl('votes', $currentSort, $currentDirection) }}" class="inline-flex items-center justify-center gap-1 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            Total Votes
                            <span class="material-symbols-outlined text-sm leading-none {{ $currentSort === 'votes' ? 'opacity-100' : 'opacity-20' }}">{{ $sortIcon('votes', $currentSort, $currentDirection) }}</span>
                        </a>
                    </th>
                    <th class="py-3 px-4">
                        <a href="{{ $sortUrl('last_activity', $currentSort, $currentDirection) }}" class="inline-flex items-center gap-1 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            Last Activity
                            <span class="material-symbols-outlined text-sm leading-none {{ $currentSort === 'last_activity' ? 'opacity-100' : 'opacity-20' }}">{{ $sortIcon('last_activity', $currentSort, $currentDirection) }}</span>
                        </a>
                    </th>
                    <th class="py-3 pl-4 pr-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-slate-700 dark:text-slate-300">
                @foreach($users as $user)
                    <x-cyber.admin-user-row :user="$user" />
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $users->onEachSide(1)->links() }}
        </div>
    @endif
</div>
