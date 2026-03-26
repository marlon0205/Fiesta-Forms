<?php

namespace App\Http\Controllers;

use App\Models\Product_Categories;
use App\Models\Service_Categories;
use App\Models\Survey;
use App\Models\Votes;
use App\Models\VoteAnswers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SurveyController extends Controller
{
class SurveyController extends Controller {

    /**
     * Display the survey detail page for both customers (to vote) and guests (to see results).
     */
    public function show(Survey $survey){

        $survey->load(['questions.answerOptions', 'serviceCategory', 'productCategory']);
        $totalSubmissions = $survey->votes()->count();

        // Calculate results
        // Loops through the questions and possible answerOptions to calculate the percentages and count the votes
        // At the end each question will be put in to the results array with their id as index.
        // The results array then will be send into the frontend
        $results = [];
        foreach ($survey->questions as $question) {
            $questionResults = [];
            foreach ($question->answerOptions as $option) {
                $optionVotes = $question->voteAnswers()->where('option_id', $option->option_id)->count();
                $percentage = $totalSubmissions > 0 ? round(($optionVotes / $totalSubmissions) * 100) : 0;
                $questionResults[] = [
                    'label' => $option->option_text,
                    'votes' => $optionVotes,
                    'percentage' => $percentage,
                ];
            }
            $results[$question->question_id] = [
                'question_text' => $question->question_text,
                'options' => $questionResults,
            ];
        }

        return view('cyber.form-detail', [
            'survey' => $survey,
            'results' => $results,
            'totalSubmissions' => $totalSubmissions,
            'id' => $survey->survey_id,
        ]);
    }

    /**
     * Store a new vote for the survey.
     */
    public function showView(Survey $survey)
    {
        // Lade die Beziehungen
        $survey->load(['questions.answerOptions', 'user', 'serviceCategory', 'productCategory']);
    public function vote(Request $request, Survey $survey){
        // Check if user has already voted
        if ($survey->votes()->where('user_id', Auth::id())->exists()) {
            return redirect()->back()->with('error', 'You have already voted on this survey.');
        }

        // Ensure every question in the survey has an answer
        $questionCount = $survey->questions->count();

        // the first array in the validate method are the rules. The second array are the error messages.
        $validated = $request->validate([
            // 1. Check the array itself: Make sure they submitted exactly the right number of answers
            'questions'   => ['required', 'array', 'size:' . $questionCount],

            // Apply rule to every answer in the array
            'questions.*' => ['required', 'exists:answer_options,option_id'],
        ], [
            'questions.size' => 'Please answer all questions before submitting.',
            'questions.*.exists' => 'One of the selected options is invalid.',
        ]);


        // loops through the user's answers. For every answer it creates a new record.
        // "use ($validated, $survey)" is necessary so we can access the data inside the transaction function.
        // first we create the votes for the user and the survey then we save the VoteAnswers.
        //
        // Votes only save the userId and SurveyId so we know if they voted for a specific survey.
        // VoteAnswers saves the answers a user has given to a specific question per survey.
        try {
            DB::transaction(function () use ($validated, $survey) {
                $vote = Votes::create([
                    'survey_id' => $survey->survey_id,
                    'user_id' => Auth::id(),
                ]);

                foreach ($validated['questions'] as $questionId => $optionId) {
                    VoteAnswers::create([
                        'vote_id' => $vote->vote_id,
                        'question_id' => $questionId,
                        'option_id' => $optionId,
                    ]);
                }
            });

            return redirect()->route('dashboard.explore')->with('success', 'Thank you for your feedback!');
        } catch (\Exception $e) {
            Log::error('Failed to store vote: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to submit vote. Please try again.');
        }
    }

    /**
     * Shows the survey creation page.
     */
    public function createView()
    {
        return view('surveys.create', [
            'categories' => $this->categoryNames(),
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string|max:255',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*' => 'required|string|max:255',
        ]);
    }

    /**
     * Zeigt eine spezifische Umfrage als HTML an (für Blade).
     * GET admin/survey/{survey}
     */
    public function editView(Survey $survey)
    {
        // Lade die Beziehungen
        $survey->load(['questions.answerOptions', 'user', 'serviceCategory', 'productCategory']);

        // Gib die Blade-View zurück und übergebe die Variable $survey
        return view('surveys.edit', [
            'survey' => $survey,
            'categories' => $this->categoryNames(),
        ]);
    }

    /**
     * Speichert eine neue Umfrage inkl. Fragen und Optionen.
     * POST /surveys
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->surveyValidationRules());

        /**
         * DB::transaction explanation: https://laravel.com/docs/12.x/database#database-transactions
         * Short version: method to run a Set of operations
         *
         * Why transaction? -> If the server crashes in the middle of an operation everything will be reset.
         */
        DB::transaction(function () use ($validated) {
            /**
             * search for the ID based on the name of the category
             */
            $categoryIds = $this->resolveCategoryIds($validated['category']);

            /**
             * Create the survey
             */
            $survey = Survey::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? '',
                'user_id' => Auth::id(),
                'service_category_id' => $categoryIds['service_category_id'],
                'product_category_id' => $categoryIds['product_category_id'],
                'is_active' => (bool) $validated['is_active'],
                'duration_days' => 30, // Default, if not set
            ]);

            $this->createQuestionsAndOptions($survey, $validated['questions']);
        });
            /**
             * Create the questions and answer options
             *
             * Iterate through each questions and the answer options.
             * questions will only be saved if the question has answer options.
             */
            foreach ($validated['questions'] as $qData) {
                $question = $survey->questions()->create([
                    'question_text' => $qData['text'],
                ]);

        return redirect()->route('dashboard.admin')->with('success', 'Survey created successfully.');
    }

    /**
     * Updates an existing survey including questions and options.
     */
    public function update(Request $request, Survey $survey)
    {
        $validated = $request->validate($this->surveyValidationRules());

        $hadVotes = $survey->votes()->exists();

        DB::transaction(function () use ($validated, $survey, $hadVotes) {
            $categoryIds = $this->resolveCategoryIds($validated['category']);

            $survey->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? '',
                'service_category_id' => $categoryIds['service_category_id'],
                'product_category_id' => $categoryIds['product_category_id'],
                'is_active' => (bool) $validated['is_active'],
            ]);

            if ($hadVotes) {
                $survey->votes()->delete();
            }

            foreach ($survey->questions as $question) {
                $question->answerOptions()->delete();
            }

            $survey->questions()->delete();

            $this->createQuestionsAndOptions($survey, $validated['questions']);
        });

        $successMessage = $hadVotes
            ? 'Survey updated. Existing votes were reset because questions changed.'
            : 'Survey updated successfully.';

        return redirect()->route('dashboard.admin')->with('success', $successMessage);
    }

    /**
     * Display the admin dashboard with all surveys
     */
    public function index()
    {
        $sort = request('sort', 'created');
        $direction = request('direction', 'desc') === 'asc' ? 'asc' : 'desc';

        $allowedSorts = [
            'title',
            'questions',
            'responses',
            'last_response',
            'status',
            'created',
        ];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'created';
        }

        $surveysQuery = Survey::with(['user', 'serviceCategory', 'productCategory'])
            ->withCount(['votes', 'questions'])
            ->withMax('votes as last_response_at', 'created_at');

        switch ($sort) {
            case 'title':
                $surveysQuery->orderBy('title', $direction);
                break;
            case 'questions':
                $surveysQuery->orderBy('questions_count', $direction);
                break;
            case 'responses':
                $surveysQuery->orderBy('votes_count', $direction);
                break;
            case 'last_response':
                $surveysQuery->orderBy('last_response_at', $direction);
                break;
            case 'status':
                $surveysQuery->orderBy('is_active', $direction);
                break;
            case 'created':
            default:
                $surveysQuery->orderBy('created_at', $direction);
                break;
        }

        $surveys = $surveysQuery
            ->orderBy('survey_id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('cyber.admin', compact('surveys'));
    }

    /**
     * Delete a survey and all related data
     */
    public function destroy(Survey $survey)
    {
        try {
            $surveyTitle = $survey->title;

            // Delete related records first
            DB::transaction(function () use ($survey) {
                // Delete all votes for this survey
                $survey->votes()->delete();

                // Delete all answer options for questions in this survey
                foreach ($survey->questions as $question) {
                    $question->answerOptions()->delete();
                }

                // Delete all questions for this survey
                $survey->questions()->delete();

                // Finally delete the survey itself
                $survey->delete();
            });

            return redirect()->route('dashboard.admin')
                ->with('success', "Survey '{$surveyTitle}' has been successfully deleted");
        } catch (\Exception $e) {
            Log::error('Failed to delete survey: '.$e->getMessage());

            return redirect()->route('dashboard.admin')
                ->with('error', 'Failed to delete survey: '.$e->getMessage());
        }
    }

    /**
     * Returns all available category names from both category tables.
     */
    private function categoryNames()
    {
        return Service_Categories::query()->pluck('name')
            ->merge(Product_Categories::query()->pluck('name'))
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * Validation rules for survey create and update.
     */
    private function surveyValidationRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'category' => [
                'required',
                'string',
                Rule::in($this->categoryNames()->all()),
            ],
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string|max:255',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*' => 'required|string|max:255',
        ];
    }

    /**
     * Resolves category IDs by a shared category name.
     */
    private function resolveCategoryIds(string $categoryName): array
    {
        $serviceCategory = Service_Categories::where('name', $categoryName)->first();
        $productCategory = Product_Categories::where('name', $categoryName)->first();

        return [
            'service_category_id' => $serviceCategory?->service_category_id,
            'product_category_id' => $productCategory?->product_category_id,
        ];
    }

    /**
     * Creates all questions and their options for the given survey.
     */
    private function createQuestionsAndOptions(Survey $survey, array $questions): void
    {
        foreach ($questions as $questionData) {
            $question = $survey->questions()->create([
                'question_text' => $questionData['text'],
            ]);

            foreach ($questionData['options'] as $optionText) {
                $question->answerOptions()->create([
                    'option_text' => $optionText,
                ]);
            }
        }
    }
}
