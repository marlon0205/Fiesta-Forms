@extends('layouts.cyber')

@section('title', 'Edit Survey')

@php
    $selectedCategory = old('category', $survey->serviceCategory?->name ?? $survey->productCategory?->name);
    $initialIsActive = (bool) old('is_active', $survey->is_active);

    $existingQuestions = $survey->questions
        ->map(function ($question) {
            return [
                'text' => $question->question_text,
                'options' => $question->answerOptions->pluck('option_text')->values()->all(),
            ];
        })
        ->values()
        ->all();

    $initialQuestions = old('questions', $existingQuestions);
@endphp

@section('content')
<div class="max-w-5xl mx-auto py-4 fade-in" x-data="{ isActive: @js($initialIsActive) }">
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white">Edit Survey</h2>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Update survey details, questions, and answer options.</p>
        </div>
        <div class="flex items-center gap-2">
            <input type="hidden" name="is_active" form="survey-form" :value="isActive ? 1 : 0">
            <button type="button" @click="isActive = !isActive" :class="isActive ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200'" class="px-4 py-2 rounded-xl font-semibold transition-colors inline-flex items-center gap-2">
                <span class="w-2 h-2 rounded-full" :class="isActive ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                <span x-text="isActive ? 'active' : 'inactive'" class="uppercase tracking-wide text-xs font-black"></span>
            </button>
            <a href="{{ route('dashboard.admin') }}" class="px-4 py-2 rounded-xl bg-white/70 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold shadow hover:bg-white dark:hover:bg-slate-700 transition-colors">
                Back to Admin
            </a>
        </div>
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
        'action' => route('admin.survey.update', $survey->survey_id),
        'method' => 'PATCH',
        'submitLabel' => 'Save Changes',
        'categories' => $categories,
        'selectedCategory' => $selectedCategory,
        'titleValue' => $survey->title,
        'descriptionValue' => $survey->description,
        'initialQuestions' => $initialQuestions,
        'formId' => 'survey-form',
    ])
</div>
@endsection
