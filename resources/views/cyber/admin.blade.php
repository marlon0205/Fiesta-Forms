@extends('layouts.cyber')

@section('title', 'Admin Console')

@section('content')
@php
    $activeTab = in_array(request('tab', 'forms'), ['forms', 'users', 'integrations', 'rewards'], true) ? request('tab', 'forms') : 'forms';
@endphp

<div class="max-w-7xl mx-auto py-4 fade-in">
    <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white">Admin Console</h2>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Manage surveys, users, rewards, and integrations.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.survey.create') }}" class="bg-slate-900 dark:bg-indigo-600 hover:bg-slate-800 dark:hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">add_circle</span> New Form
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-rose-500/10 border border-rose-500/50 text-rose-500 rounded-xl text-sm font-bold">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="glass-panel dark:bg-slate-800/50 rounded-3xl shadow-xl overflow-hidden min-h-[500px] border-0">
        <div class="flex border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 backdrop-blur">
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'forms', 'page' => 1]) }}" class="px-8 py-4 text-sm font-bold transition-colors {{ $activeTab === 'forms' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400 bg-white/50 dark:bg-slate-800/50' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">Forms</a>
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'users', 'users_page' => 1]) }}" class="px-8 py-4 text-sm font-bold transition-colors {{ $activeTab === 'users' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400 bg-white/50 dark:bg-slate-800/50' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">Users</a>
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'integrations']) }}" class="px-8 py-4 text-sm font-bold transition-colors {{ $activeTab === 'integrations' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400 bg-white/50 dark:bg-slate-800/50' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">Integrations</a>
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'rewards', 'rewards_page' => 1]) }}" class="px-8 py-4 text-sm font-bold transition-colors {{ $activeTab === 'rewards' ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400 bg-white/50 dark:bg-slate-800/50' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">Rewards</a>
        </div>

        @if($activeTab === 'users')
            <x-cyber.admin-users-tab :users="$users" />
        @elseif($activeTab === 'integrations')
            <x-cyber.admin-integrations-tab />
        @elseif($activeTab === 'rewards')
            <x-cyber.admin-rewards-tab :rewards="$rewards" />
        @else
            <x-cyber.admin-forms-tab :surveys="$surveys" />
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showToast({{ Js::from(session('success')) }});
        @endif
        @if(session('error'))
            showToast({{ Js::from(session('error')) }});
        @endif
    });
</script>
@endpush
