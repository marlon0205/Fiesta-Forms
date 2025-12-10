{{-- resources/views/surveys/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Survey Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Zugriff auf direkte Attribute der Umfrage --}}
                    <h1><strong>Titel:</strong> {{ $survey->title }}</h1>
                    <p><strong>Beschreibung:</strong> {{ $survey->description }}</p>

                    {{-- Zugriff auf die User-Beziehung --}}
                    <p><strong>Erstellt von:</strong> {{ $survey->user->name }}</p>

                    <hr class="my-4">

                    <h3 class="text-lg font-semibold">Fragen:</h3>

                    {{-- Durch die Fragen loopen --}}
                    @forelse ($survey->questions as $question)
                        <div class="mt-2">
                            <p>{{ $question->question_text }}</p>

                            {{-- Optional: Durch die Antwortoptionen der Frage loopen --}}
                            <ul>
                                @foreach ($question->answerOptions as $option)
                                    <li>- {{ $option->option_text }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <p>Diese Umfrage hat noch keine Fragen.</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
