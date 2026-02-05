@extends('layouts.cyber')

@section('title', 'Dashboard')

@section('content')
@php
// Mock data for demonstration
$forms = [
    ['id' => 101, 'title' => 'Q3 Product Roadmap', 'description' => 'Help us prioritize features for the upcoming quarter.', 'category' => 'Product Feedback', 'status' => 'active', 'submissions' => 1240],
    ['id' => 102, 'title' => 'Employee Satisfaction', 'description' => 'Anonymous workplace environment survey.', 'category' => 'HR & Culture', 'status' => 'expired', 'submissions' => 85],
    ['id' => 103, 'title' => 'Cafeteria Menu', 'description' => 'Voting for new vendor options.', 'category' => 'HR & Culture', 'status' => 'active', 'submissions' => 342],
];

$topVoters = [
    ['name' => 'Sarah Connor', 'votes' => 152],
    ['name' => 'John Smith', 'votes' => 140],
    ['name' => 'Emily Chen', 'votes' => 128],
];

$totalForms = 248;
$activeForms = 12;
$totalSubs = 85400;
$impactScore = '98%';
@endphp

<div class="max-w-7xl mx-auto py-4 fade-in">
    <div class="mb-8">
        <h2 class="text-4xl font-black text-slate-800 dark:text-white mb-2">
            Hello, <span class="text-gradient">{{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Guest' }}</span>
        </h2>
        <p class="text-slate-500 dark:text-slate-400 font-medium">Here's what's happening in your workspace today.</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <x-cyber.kpi-card
            label="Total Forms"
            :value="$totalForms"
            icon="description"
            gradient="from-blue-500 to-cyan-400"
        />
        <x-cyber.kpi-card
            label="Active Polls"
            :value="$activeForms"
            icon="pending_actions"
            gradient="from-emerald-500 to-teal-400"
        />
        <x-cyber.kpi-card
            label="Responses"
            :value="number_format($totalSubs)"
            icon="mark_chat_read"
            gradient="from-violet-500 to-purple-400"
        />
        <x-cyber.kpi-card
            label="Impact Score"
            :value="$impactScore"
            icon="trending_up"
            gradient="from-orange-500 to-amber-400"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Forms List -->
        <div class="lg:col-span-2 glass-card rounded-3xl p-6 md:p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-xl text-slate-800 dark:text-white">Recent Activity</h3>
                <a href="{{ route('dashboard.explore') }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-bold hover:underline">See All</a>
            </div>
            <div class="space-y-4">
                @foreach(array_slice($forms, 0, 3) as $form)
                    <x-cyber.form-list-row :form="$form" />
                @endforeach
            </div>
        </div>

        <!-- Top Voters Leaderboard -->
        <x-cyber.top-voters :voters="$topVoters" />
    </div>
</div>
@endsection

