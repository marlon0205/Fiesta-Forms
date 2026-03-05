<div class="lg:col-span-2 glass-card rounded-3xl p-6 md:p-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-xl text-slate-800 dark:text-white">Recent Activity</h3>
        <a href="{{ route('dashboard.explore') }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-bold hover:underline">See All</a>
    </div>
    <div class="space-y-4">
        @foreach($recentSurveys as $form)
            <x-cyber.form-list-row :form="$form" />
        @endforeach
    </div>
</div>
