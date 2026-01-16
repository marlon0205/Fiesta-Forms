<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Service_Categories;
use App\Models\Product_Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller {


    /**
     * Zeigt eine spezifische Umfrage als HTML an (für Blade).
     * GET /surveys/{survey}
     */
    public function showView(Survey $survey) {
        // Lade die Beziehungen
        $survey->load(['questions.answerOptions', 'user', 'serviceCategory', 'productCategory']);

        // Gib die Blade-View zurück und übergebe die Variable $survey
        return view('surveys.show', compact('survey'));
    }

    /**
     * Speichert eine neue Umfrage inkl. Fragen und Optionen.
     * POST /surveys
     */
    public function store(Request $request) {
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
                    'question_text' => $qData['text']
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

        return redirect()->route('dashboard')->with('success', 'Mission launched successfully!');
    }

}
