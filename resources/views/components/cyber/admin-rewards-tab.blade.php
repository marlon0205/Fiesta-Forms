@props(['rewards'])

<div class="p-6 space-y-8 overflow-x-auto">
    <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-700 p-5">
        <form method="POST" action="{{ route('dashboard.admin.rewards.store') }}" class="grid grid-cols-1 lg:grid-cols-[1fr_1.4fr_160px_auto] gap-4 items-end">
            @csrf
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Reward Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow text-slate-700 dark:text-slate-200">
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Description</label>
                <input type="text" name="description" value="{{ old('description') }}" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow text-slate-700 dark:text-slate-200">
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Points</label>
                <input type="number" name="points_required" value="{{ old('points_required') }}" min="1" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow text-slate-700 dark:text-slate-200 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
            </div>
            <button type="submit" class="bg-slate-900 dark:bg-indigo-600 hover:bg-slate-800 dark:hover:bg-indigo-500 text-white px-5 py-3 rounded-xl text-sm font-bold shadow-lg transition-all inline-flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-lg">add_circle</span>
                Add Reward
            </button>
        </form>
    </div>

    @if($rewards->count() === 0)
        <div class="text-center py-12">
            <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600">workspace_premium</span>
            <p class="mt-4 text-slate-500 dark:text-slate-400 font-medium">No rewards found</p>
            <p class="text-sm text-slate-400 dark:text-slate-500">Create point thresholds to unlock profile rewards</p>
        </div>
    @else
        <table class="w-full min-w-[900px] border-collapse text-left align-middle">
            <thead>
                <tr class="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-slate-200 dark:border-slate-700">
                    <th class="py-3 pl-4 pr-4">Reward</th>
                    <th class="py-3 px-4">Description</th>
                    <th class="py-3 px-4 text-center">Points Required</th>
                    <th class="py-3 pl-4 pr-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-slate-700 dark:text-slate-300">
                @foreach($rewards as $reward)
                    <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="py-5 pl-4 pr-4 align-top">
                            <form id="reward-update-{{ $reward->reward_id }}" method="POST" action="{{ route('dashboard.admin.rewards.update', $reward) }}">
                                @csrf
                                @method('patch')
                                <input type="text" name="name" value="{{ old('name', $reward->name) }}" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow text-slate-900 dark:text-white">
                            </form>
                        </td>
                        <td class="py-5 px-4 align-top">
                            <input form="reward-update-{{ $reward->reward_id }}" type="text" name="description" value="{{ old('description', $reward->description) }}" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow text-slate-700 dark:text-slate-200">
                        </td>
                        <td class="py-5 px-4 align-top">
                            <input form="reward-update-{{ $reward->reward_id }}" type="number" name="points_required" value="{{ old('points_required', $reward->points_required) }}" min="1" required class="w-36 mx-auto block bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold text-center tabular-nums focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow text-slate-900 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        </td>
                        <td class="py-5 pl-4 pr-4 text-right align-middle">
                            <div class="flex items-center justify-end gap-2">
                                <button type="submit" form="reward-update-{{ $reward->reward_id }}" class="w-8 h-8 rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-400 transition-colors" title="Save reward">
                                    <span class="material-symbols-outlined text-[18px]">save</span>
                                </button>
                                <form method="POST" action="{{ route('dashboard.admin.rewards.destroy', $reward) }}" onsubmit="return confirm('Are you sure you want to delete this reward?');">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="w-8 h-8 rounded-full hover:bg-rose-100 dark:hover:bg-rose-900 flex items-center justify-center text-rose-600 dark:text-rose-400 transition-colors" title="Delete reward">
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
            {{ $rewards->onEachSide(1)->links() }}
        </div>
    @endif
</div>
