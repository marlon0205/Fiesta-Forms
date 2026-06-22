<form id="{{ $formId ?? 'survey-form' }}" method="POST" action="{{ $action }}" x-data="surveyForm(@js($initialQuestions), @js($isActiveValue ?? false))" class="space-y-6">
    @csrf
    @if (($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    <div class="glass-panel dark:bg-slate-800/50 rounded-3xl shadow-xl border-0 p-6 space-y-5">
        <div>
            <label for="title" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Title</label>
            <input id="title" name="title" type="text" value="{{ old('title', $titleValue ?? '') }}" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/70 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Customer Experience Survey">
        </div>

        <div>
            <label for="description" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Description</label>
            <textarea id="description" name="description" rows="3" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/70 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Short context for participants">{{ old('description', $descriptionValue ?? '') }}</textarea>
        </div>

        <div>
            <label for="category" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Category</label>
            <select id="category" name="category" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/70 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="" disabled {{ old('category', $selectedCategory ?? null) ? '' : 'selected' }}>Choose a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(old('category', $selectedCategory ?? null) === $category)>{{ $category }}</option>
                @endforeach
            </select>
        </div>

        <div class="border-t border-slate-200 dark:border-slate-700 pt-5 space-y-4">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wide">Survey Settings</h3>

            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-200">Status</span>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Control whether this survey accepts responses</p>
                </div>
                <div>
                    <input type="hidden" name="is_active" :value="isActive ? 1 : 0">
                    <button type="button" @click="isActive = !isActive"
                        :class="isActive ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200'"
                        class="px-4 py-2 rounded-xl font-semibold transition-colors inline-flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full" :class="isActive ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                        <span x-text="isActive ? 'active' : 'inactive'" class="uppercase tracking-wide text-xs font-black"></span>
                    </button>
                </div>
            </div>

            <div>
                <label for="expires_at" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">
                    Expiry Date <span class="font-normal text-slate-500 dark:text-slate-400">(optional)</span>
                </label>
                <input id="expires_at" name="expires_at" type="date"
                    value="{{ old('expires_at', $expiresAtValue ?? '') }}"
                    class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/70 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Survey deactivates automatically on this date.</p>
            </div>
        </div>
    </div>

    <div class="glass-panel dark:bg-slate-800/50 rounded-3xl shadow-xl border-0 p-6 space-y-6">
        <h3 class="text-xl font-black text-slate-800 dark:text-white">Questions</h3>

        <template x-for="(question, questionIndex) in questions" :key="questionIndex">
            <div data-question-card class="rounded-2xl border border-slate-200 dark:border-slate-700 p-4 space-y-4 bg-white/60 dark:bg-slate-900/40">
                <div class="flex items-center justify-between gap-3">
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-200" x-text="`Question ${questionIndex + 1}`"></label>
                    <button type="button" @click="removeQuestion(questionIndex)" x-show="questions.length > 1" class="text-rose-600 dark:text-rose-400 text-sm font-semibold hover:underline">Remove</button>
                </div>

                <input type="text" :name="`questions[${questionIndex}][text]`" x-model="question.text" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/70 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Write your question">

                <div class="space-y-2">
                    <div class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">Answer Options</div>

                    <template x-for="(option, optionIndex) in question.options" :key="optionIndex">
                        <div class="flex items-center gap-2">
                            <input type="text" :name="`questions[${questionIndex}][options][${optionIndex}]`" x-model="question.options[optionIndex]" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/70 focus:ring-indigo-500 focus:border-indigo-500" :placeholder="`Option ${optionIndex + 1}`">
                            <button type="button" @click="removeOption(questionIndex, optionIndex)" :disabled="question.options.length <= 2" class="px-3 py-2 rounded-xl text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900 disabled:opacity-40 disabled:cursor-not-allowed">-</button>
                        </div>
                    </template>

                    <button type="button" @click="addOption(questionIndex)" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">+ Add Option</button>
                </div>
            </div>
        </template>

        <div x-ref="questionsEnd"></div>

        <div class="pt-1">
            <button type="button" @click="addQuestion()" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-500 transition-colors">
                Add Question
            </button>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('dashboard.admin') }}" class="px-5 py-2.5 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">Cancel</a>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-500 shadow-lg transition-colors">{{ $submitLabel }}</button>
    </div>
</form>

@once
    @push('scripts')
        <script>
            function surveyForm(initialQuestions, initialIsActive) {
                const sanitizedQuestions = Array.isArray(initialQuestions) && initialQuestions.length
                    ? initialQuestions
                    : [{text: '', options: ['', '']}];

                const normalizedQuestions = sanitizedQuestions.map(question => ({
                    text: question.text ?? '',
                    options: Array.isArray(question.options) && question.options.length >= 2
                        ? question.options
                        : ['', '']
                }));

                return {
                    questions: normalizedQuestions,
                    isActive: initialIsActive ?? false,

                    addQuestion() {
                        this.questions.push({text: '', options: ['', '']});

                        this.$nextTick(() => {
                            const cards = document.querySelectorAll('[data-question-card]');
                            cards[cards.length - 1]?.scrollIntoView({behavior: 'smooth', block: 'center'});
                        });
                    },

                    removeQuestion(index) {
                        this.questions.splice(index, 1);
                    },

                    addOption(questionIndex) {
                        this.questions[questionIndex].options.push('');
                    },

                    removeOption(questionIndex, optionIndex) {
                        if (this.questions[questionIndex].options.length <= 2) {
                            return;
                        }

                        this.questions[questionIndex].options.splice(optionIndex, 1);
                    }
                };
            }
        </script>
    @endpush
@endonce
