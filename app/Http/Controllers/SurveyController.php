<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Service_Categories;
use App\Models\Product_Categories;
use App\Models\Votes;
use App\Models\VoteAnswers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * Speichert eine neue Umfrage inkl. Fragen und Optionen.
     * POST /surveys
     */
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
            $serviceCat = Service_Categories::where('name', $validated['category'] ?? '')->first();
            $productCat = Product_Categories::where('name', $validated['category'] ?? '')->first();

            /**
             * Create the survey
             */
            $survey = Auth::user()->surveys()->create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? '',
                'service_category_id' => $serviceCat?->service_category_id,
                'product_category_id' => $productCat?->product_category_id,
                'is_active' => true,
                'duration_days' => 30, // Default, if not set
            ]);

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

                foreach ($qData['options'] as $optText) {
                    // Only save the question if it has answer options
                    if (filled($optText)) {
                        /* Check why option_text is guarded. unguarding will make it fillable in Questions.php, i dont know why lol
                         * Edit: False Positive. Intellij checks for 'option_text' in Questions because the chain started there.
                         */
                        $question->answerOptions()->create(['option_text' => $optText]);
                    }
                }
            }
        });

        return redirect()->route('dashboard.home')->with('success', 'Mission launched successfully!');
    }

    /**
     * Display the admin dashboard with all surveys
     */
    public function index()
    {
        $surveys = Survey::with(['user', 'serviceCategory', 'productCategory', 'votes'])
            ->withCount('votes')
            ->orderBy('created_at', 'desc')
            ->get();

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
            Log::error('Failed to delete survey: ' . $e->getMessage());
            return redirect()->route('dashboard.admin')
                ->with('error', 'Failed to delete survey: ' . $e->getMessage());
        }
    }
}
