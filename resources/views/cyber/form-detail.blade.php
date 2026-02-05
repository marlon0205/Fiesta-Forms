@extends('layouts.cyber')

@section('title', 'Form Details')

@section('content')
@php
// Mock form data
$form = [
    'id' => $id ?? 101,
    'title' => 'Q3 Product Roadmap',
    'description' => 'Help us prioritize features for the upcoming quarter.',
    'category' => 'Product Feedback',
    'status' => 'active',
    'submissions' => 1240,
    'created' => '2025-10-15',
    'questions' => [
        ['id' => 1, 'text' => 'What is your critical feature?', 'type' => 'radio', 'options' => ['Dark Mode', 'API Access', 'Mobile App']],
        ['id' => 2, 'text' => 'How satisfied are you?', 'type' => 'range', 'min' => 1, 'max' => 10],
    ],
    'results' => [
        'Dark Mode' => 45,
        'API Access' => 30,
        'Mobile App' => 25,
    ]
];

$canTake = auth()->check() && auth()->user()->role === 'Customer' && $form['status'] === 'active';
@endphp

<div class="p-2 md:p-6 fade-in h-full">
    @if($canTake)
    <!-- Form Submission View -->
    <div class="glass-panel dark:bg-slate-800/80 rounded-3xl shadow-2xl p-8 md:p-12 max-w-3xl mx-auto relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

        <div class="mb-10">
            <span class="px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-300 text-xs font-black uppercase tracking-wider mb-4 inline-block shadow-sm">{{ $form['category'] }}</span>
            <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-4 leading-tight">{{ $form['title'] }}</h1>
            <p class="text-lg text-slate-600 dark:text-slate-300 leading-relaxed">{{ $form['description'] }}</p>
        </div>

        <form onsubmit="event.preventDefault(); showToast('✨ Response submitted! +50 XP'); setTimeout(() => window.location.href = '{{ route('dashboard.home') }}', 1500);" class="space-y-10">
            @foreach($form['questions'] as $index => $question)
            <div class="space-y-4 animate-float" style="animation-delay: {{ $index * 200 }}ms; animation-duration: 8s;">
                <label class="block text-lg font-bold text-slate-800 dark:text-slate-200">
                    <span class="text-indigo-500 mr-2">0{{ $index + 1 }}.</span> {{ $question['text'] }}
                </label>

                @if($question['type'] === 'radio')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                    @foreach($question['options'] as $optIndex => $option)
                    <label class="relative flex items-center p-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 cursor-pointer hover:border-indigo-500 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all group">
                        <input type="radio" name="q-{{ $question['id'] }}" value="{{ $option }}" class="peer sr-only">
                        <div class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 peer-checked:border-indigo-500 peer-checked:bg-indigo-500 mr-3 transition-all flex items-center justify-center">
                            <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white">{{ $option }}</span>
                        <div class="absolute inset-0 rounded-xl ring-2 ring-indigo-500 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                    </label>
                    @endforeach
                </div>
                @elseif($question['type'] === 'range')
                <div class="mt-6 px-2">
                    <input type="range" min="{{ $question['min'] }}" max="{{ $question['max'] }}" class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                    <div class="flex justify-between text-xs font-bold text-slate-400 mt-3 uppercase tracking-wider">
                        <span>Low Impact</span>
                        <span>High Impact</span>
                    </div>
                </div>
                @endif
            </div>
            @endforeach

            <div class="pt-8 flex justify-end gap-4 border-t border-slate-200 dark:border-slate-700">
                <button type="button" onclick="window.location.href='{{ route('dashboard.explore') }}'" class="px-8 py-3 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">Cancel</button>
                <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105 transition-all">Submit Feedback</button>
            </div>
        </form>
    </div>
    @else
    <!-- Results View -->
    <div class="max-w-5xl mx-auto">
        <button onclick="window.location.href='{{ route('dashboard.explore') }}'" class="mb-6 flex items-center text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm font-bold transition-colors uppercase tracking-wider">
            <span class="material-symbols-outlined text-lg mr-2">arrow_back</span> Back to Forms
        </button>

        <div class="glass-panel dark:bg-slate-800/60 rounded-3xl shadow-2xl border-0 overflow-hidden">
            <div class="p-10 bg-slate-50/50 dark:bg-slate-900/50 border-b border-white/20 dark:border-slate-700/50 backdrop-blur-sm">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-2">{{ $form['title'] }}</h1>
                <div class="flex items-center gap-6 text-sm font-medium text-slate-500 dark:text-slate-400">
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined">group</span> {{ number_format($form['submissions']) }} Responses</span>
                    <span class="flex items-center gap-2 px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold uppercase">{{ $form['status'] }}</span>
                </div>
            </div>
            <div class="p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="space-y-6">
                        <h4 class="font-bold text-slate-400 uppercase text-xs tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2">Top Responses</h4>
                        @foreach($form['results'] as $key => $val)
                        <div class="group">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-slate-700 dark:text-slate-300 font-semibold group-hover:text-indigo-500 transition-colors">{{ $key }}</span>
                                <span class="font-black text-slate-900 dark:text-white">{{ $val }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-3 overflow-hidden shadow-inner">
                                <div class="h-3 rounded-full bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500" style="width: {{ $val }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="relative h-72 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-6xl font-black text-slate-800 dark:text-white mb-2">{{ $form['submissions'] }}</div>
                            <div class="text-sm uppercase font-bold text-slate-400">Total Submissions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

