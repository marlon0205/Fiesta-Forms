@extends('layouts.cyber')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-4 fade-in">
    <div class="mb-8">
        <h2 class="text-4xl font-black text-slate-800 dark:text-white mb-2">
            Hello, <span class="text-gradient">{{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Guest' }}</span>
        </h2>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Here's what's happening in your workspace today.</p>
    </div>

    <!-- KPI Cards via StatsOverview Component -->
    <!-- inserts file resources/views/components/dashboard/stats-overview.blade.php -->
    <x-dashboard.stats-overview />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Forms List via RecentActivity Component -->
        <x-dashboard.recent-activity />

        <!-- Top Voters Leaderboard via TopUsers Component -->
        <x-dashboard.top-users />
    </div>
</div>
@endsection

