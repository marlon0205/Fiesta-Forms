@extends('layouts.cyber')

@section('title', 'Create Survey')

@section('content')
<div class="max-w-5xl mx-auto py-4 fade-in">
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white">Create Survey</h2>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Set up a new form with questions and answer options.</p>
        </div>
        <a href="{{ route('dashboard.admin') }}" class="px-4 py-2 rounded-xl bg-white/70 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold shadow hover:bg-white dark:hover:bg-slate-700 transition-colors">
            Back to Admin
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 dark:border-rose-900 bg-rose-50/80 dark:bg-rose-900/20 px-5 py-4">
            <p class="font-bold text-rose-700 dark:text-rose-300 mb-2">Please fix the following issues:</p>
            <ul class="list-disc pl-5 text-sm text-rose-700 dark:text-rose-300 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('surveys._form', [
        'action' => route('admin.survey.store'),
        'method' => 'POST',
        'submitLabel' => 'Create Survey',
        'categories' => $categories,
        'selectedCategory' => null,
        'titleValue' => '',
        'descriptionValue' => '',
        'initialQuestions' => old('questions', [['text' => '', 'options' => ['', '']]]),
    ])
</div>
@endsection
