@extends('layouts.cyber')

@section('title', 'Admin Console')

@section('content')
<div class="max-w-7xl mx-auto py-4 fade-in">
    <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white">Admin Console</h2>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Manage surveys, users, and integrations.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="showToast('Create form feature coming soon')" class="bg-slate-900 dark:bg-indigo-600 hover:bg-slate-800 dark:hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">add_circle</span> New Form
            </button>
        </div>
    </div>

    <div class="glass-panel dark:bg-slate-800/50 rounded-3xl shadow-xl overflow-hidden min-h-[500px] border-0">
        <div class="flex border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 backdrop-blur">
            <button class="px-8 py-4 text-sm font-bold text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400 bg-white/50 dark:bg-slate-800/50">Forms</button>
            <button class="px-8 py-4 text-sm font-bold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-colors">Users</button>
            <button onclick="showToast('Integrations feature coming soon')" class="px-8 py-4 text-sm font-bold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-colors">Integrations</button>
        </div>

        <x-cyber.admin-forms-tab :surveys="$surveys" />
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showToast({!! json_encode(session('success')) !!});
        @endif
        @if(session('error'))
            showToast({!! json_encode(session('error')) !!});
        @endif
    });
</script>
@endpush

