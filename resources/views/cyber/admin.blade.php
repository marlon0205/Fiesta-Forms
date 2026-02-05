@extends('layouts.cyber')

@section('title', 'Admin Console')

@section('content')
@php
// Mock data
$forms = [
    ['id' => 101, 'title' => 'Q3 Product Roadmap', 'category' => 'Product Feedback', 'status' => 'active', 'created' => '2025-10-15', 'submissions' => 1240],
    ['id' => 102, 'title' => 'Employee Satisfaction', 'category' => 'HR & Culture', 'status' => 'expired', 'created' => '2023-01-10', 'submissions' => 85],
    ['id' => 103, 'title' => 'Cafeteria Menu', 'category' => 'HR & Culture', 'status' => 'active', 'created' => '2025-12-01', 'submissions' => 342],
    ['id' => 104, 'title' => 'IT Ticket Experience', 'category' => 'IT Support', 'status' => 'active', 'created' => '2026-01-05', 'submissions' => 12],
    ['id' => 105, 'title' => 'Holiday Party RSVP', 'category' => 'Event Registration', 'status' => 'archived', 'created' => '2023-11-01', 'submissions' => 200],
];
@endphp

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

        <div class="p-6 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-slate-200 dark:border-slate-700">
                        <th class="py-4 pl-4">Title</th>
                        <th class="py-4">Status</th>
                        <th class="py-4">Performance</th>
                        <th class="py-4 pr-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    @foreach($forms as $form)
                    <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-700/30 group transition-colors">
                        <td class="py-4 pl-4">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $form['title'] }}</div>
                            <div class="text-xs text-slate-400">{{ $form['created'] }}</div>
                        </td>
                        <td class="py-4">
                            @if($form['status'] === 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                active
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                {{ $form['status'] }}
                            </span>
                            @endif
                        </td>
                        <td class="py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold">{{ $form['submissions'] }} subs</span>
                                <div class="w-24 bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-indigo-500 h-full rounded-full" style="width: {{ min($form['submissions'] / 10, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 pr-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-50 group-hover:opacity-100 transition-opacity">
                                <button onclick="showToast('Edit feature coming soon')" class="w-8 h-8 rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-400 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <button onclick="showToast('Delete feature coming soon')" class="w-8 h-8 rounded-full hover:bg-rose-100 dark:hover:bg-rose-900 flex items-center justify-center text-rose-600 dark:text-rose-400 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

