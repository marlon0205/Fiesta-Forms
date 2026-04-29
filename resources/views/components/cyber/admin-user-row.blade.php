@props(['user'])

@php
    $roleNames = $user->roles->pluck('name')->join(', ');
    $lastActivity = $user->last_activity_at
        ? \Illuminate\Support\Carbon::createFromTimestamp($user->last_activity_at)
        : null;
@endphp

<tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-700/30 group transition-colors">
    <td class="py-5 pl-4 pr-4 align-top">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-black text-sm">
                {{ Str::upper(Str::substr($user->name, 0, 2)) }}
            </div>
            <div>
                <div class="font-bold text-slate-900 dark:text-white">{{ $user->name }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400">Joined {{ $user->created_at->format('Y-m-d') }}</div>
            </div>
        </div>
    </td>
    <td class="py-5 px-4 align-top">
        <div class="font-semibold text-slate-800 dark:text-slate-100">{{ $user->email }}</div>
    </td>
    <td class="py-5 px-4">
        <span class="inline-flex items-center gap-1.5 whitespace-nowrap px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
            {{ $roleNames ?: 'No role' }}
        </span>
    </td>
    <td class="py-5 px-4 text-center font-semibold tabular-nums text-slate-900 dark:text-slate-100">{{ $user->votes_count ?? 0 }}</td>
    <td class="py-5 px-4">
        @if($lastActivity)
            <div class="text-sm text-slate-800 dark:text-slate-100">{{ $lastActivity->diffForHumans() }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $lastActivity->format('Y-m-d H:i') }}</div>
        @else
            <span class="text-xs text-slate-400 dark:text-slate-500">No recent activity</span>
        @endif
    </td>
    <td class="py-5 pl-4 pr-4 text-right align-middle">
        <div class="flex items-center justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
            <a href="{{ route('admin.user.profile', $user->user_id) }}" class="w-8 h-8 rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-400 transition-colors" title="Open profile">
                <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
            </a>
        </div>
    </td>
</tr>
