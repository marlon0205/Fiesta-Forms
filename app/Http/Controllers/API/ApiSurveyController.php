<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\JsonResponse;

class ApiSurveyController extends Controller
{
    /**
     * Display the specified survey.
     */
    public function show(Survey $survey): JsonResponse
    {
        return response()->json($survey->load(['questions.answerOptions', 'serviceCategory', 'productCategory']));
    }
}
