@extends('layouts.cyber')

@section('title', 'Form Details')

@section('content')
@php
    $canTake = auth()->check() && auth()->user()->hasRole('customer') && $survey->is_active && !$survey->votes()->where('user_id', auth()->id())->exists();
    $alreadyVoted = auth()->check() && $survey->votes()->where('user_id', auth()->id())->exists();
    $categoryName = $survey->serviceCategory?->name ?? $survey->productCategory?->name ?? 'Uncategorized';
@endphp

<div class="p-2 md:p-6 fade-in h-full">
    @if($canTake)
    <!-- Form Submission View -->
    <div class="glass-panel dark:bg-slate-800/80 rounded-3xl shadow-2xl p-8 md:p-12 max-w-3xl mx-auto relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

        <div class="mb-10 text-center">
            <span class="px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-300 text-xs font-black uppercase tracking-wider mb-4 inline-block shadow-sm">{{ $categoryName }}</span>
            <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-4 leading-tight">{{ $survey->title }}</h1>
            <p class="text-lg text-slate-600 dark:text-slate-300 leading-relaxed">{{ $survey->description }}</p>
        </div>

        @if ($errors->any())
            <div class="mb-8 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
                <p class="font-bold">Please correct the following errors:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.form.vote', $survey->survey_id) }}" method="POST" class="space-y-12">
            @csrf
            @foreach($survey->questions as $index => $question)
            <div class="space-y-6">
                <label class="block text-xl font-black text-slate-800 dark:text-slate-200">
                    <span class="text-indigo-500 mr-2">#{{ $index + 1 }}</span> {{ $question->question_text }}
                </label>

                <div class="grid grid-cols-1 gap-3">
                    @foreach($question->answerOptions as $optIndex => $option)
                    <label class="relative flex items-center p-5 rounded-2xl border-2 {{ $errors->has('questions.'.$question->question_id) ? 'border-red-300 dark:border-red-900' : 'border-slate-200 dark:border-slate-700' }} cursor-pointer hover:border-indigo-500 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all group">
                        <input type="radio" name="questions[{{ $question->question_id }}]" value="{{ $option->option_id }}" class="peer sr-only" required>
                        <div class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 peer-checked:border-indigo-500 peer-checked:bg-indigo-500 mr-4 transition-all flex items-center justify-center">
                            <div class="w-2.5 h-2.5 rounded-full bg-white opacity-0 peer-checked:opacity-100 scale-50 peer-checked:scale-100 transition-all"></div>
                        </div>
                        <span class="text-base font-bold text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white">{{ $option->option_text }}</span>
                        <div class="absolute inset-0 rounded-2xl ring-2 ring-indigo-500 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="pt-8 flex flex-col sm:flex-row justify-end gap-4 border-t border-slate-200 dark:border-slate-700">
                <button type="button" onclick="window.location.href='{{ route('dashboard.explore') }}'" class="px-10 py-4 rounded-2xl border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">Cancel</button>
                <button type="submit" class="px-10 py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105 active:scale-95 transition-all">Submit My Answers</button>
            </div>
        </form>
    </div>
    @else
    <!-- Results View -->
    <div class="max-w-4xl mx-auto">
        <button onclick="window.location.href='{{ route('dashboard.explore') }}'" class="mb-8 flex items-center text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm font-bold transition-colors uppercase tracking-widest">
            <span class="material-symbols-outlined text-lg mr-2">arrow_back</span> Return to Explore
        </button>

        @if($alreadyVoted)
        <div class="mb-8 p-6 bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-400 rounded-3xl flex items-center gap-4 backdrop-blur-sm">
            <div class="bg-emerald-500 text-white p-2 rounded-full shadow-lg">
                <span class="material-symbols-outlined">done_all</span>
            </div>
            <div>
                <span class="font-black text-lg">Response Captured!</span>
                <p class="text-sm opacity-80">You've already contributed to this survey. View the community results below.</p>
            </div>
        </div>
        @elseif(!auth()->check())
        <div class="mb-8 p-6 bg-indigo-500/10 border border-indigo-500/20 text-indigo-700 dark:text-indigo-400 rounded-3xl flex items-center gap-4 backdrop-blur-sm">
            <div class="bg-indigo-500 text-white p-2 rounded-full shadow-lg">
                <span class="material-symbols-outlined">lock</span>
            </div>
            <div>
                <span class="font-black text-lg">Results Only</span>
                <p class="text-sm opacity-80">Login as a customer to share your feedback. Showing public statistics.</p>
            </div>
        </div>
        @endif

        <div class="glass-panel dark:bg-slate-800/60 rounded-3xl shadow-2xl border-0 overflow-hidden mb-12">
            <!-- Header with Global Counter -->
            <div class="relative p-10 bg-slate-50/50 dark:bg-slate-900/50 border-b border-white/20 dark:border-slate-700/50 backdrop-blur-sm overflow-hidden">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 relative z-10">
                    <div>
                        <span class="text-indigo-500 font-black uppercase tracking-widest text-xs mb-2 block">{{ $categoryName }}</span>
                        <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-2">{{ $survey->title }}</h1>
                        <p class="text-slate-500 dark:text-slate-400 max-w-xl">{{ $survey->description }}</p>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-slate-700 min-w-[180px] text-center">
                        <div class="text-4xl font-black text-indigo-600 dark:text-indigo-400">{{ number_format($totalSubmissions) }}</div>
                        <div class="text-[10px] uppercase font-black text-slate-400 tracking-tighter">Total Submissions</div>
                    </div>
                </div>
            </div>

            <div class="p-10 space-y-16">
                @foreach($results as $qId => $qData)
                <div class="animate-float mb-8" style="animation-delay: {{ $loop->index * 150 }}ms">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="h-10 w-1 bg-indigo-500 rounded-full"></div>
                        <h4 class="font-black text-slate-800 dark:text-slate-200 text-2xl tracking-tight">{{ $qData['question_text'] }}</h4>
                    </div>

                    <div class="space-y-4">
                        @foreach($qData['options'] as $option)
                        <div class="group">
                            <div class="flex items-center justify-between mb-3 px-1">
                                <div class="flex items-center gap-1">
                                    <span class="text-slate-700 dark:text-slate-300 font-bold group-hover:text-indigo-500 transition-colors">{{ $option['label'] }}</span>
                                    <span class="text-xs font-black px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-zinc-500">{{ $option['votes'] }} votes</span>
                                </div>
                                <span class="font-black text-indigo-600 dark:text-indigo-400">{{ $option['percentage'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-700/50 rounded-full h-4 p-1 shadow-inner relative overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 shadow-lg shadow-indigo-500/20 transition-all duration-1000 ease-out" style="width: {{ $option['percentage'] }}%">
                                    <div class="absolute inset-0 bg-white/20 w-full h-full animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
    @endif
</div>
@endsection
