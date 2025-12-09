<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    /**
     * Zeigt eine Liste aller Umfragen an.
     * GET /api/surveys
     */
    public function index()
    {
        $surveys = Survey::with(['user', 'serviceCategory', 'productCategory'])->get();
        return response()->json($surveys);
    }

    /**
     * Speichert eine neue Umfrage in der Datenbank.
     * POST /api/surveys
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,user_id',
            'service_category_id' => 'nullable|exists:service__categories,service_category_id',
            'product_category_id' => 'nullable|exists:product__categories,product_category_id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $survey = Survey::create($validator->validated());

        return response()->json($survey, 201);
    }

    /**
     * Zeigt eine spezifische Umfrage an.
     * GET /api/surveys/{survey}
     */
    public function show(Survey $survey)
    {
        // Lade die Beziehungen für eine einzelne Umfrage
        $survey->load(['questions.answerOptions', 'user', 'serviceCategory', 'productCategory']);
        return response()->json($survey);
    }

    /**
     * Aktualisiert eine bestehende Umfrage.
     * PUT/PATCH /api/surveys/{survey}
     */
    public function update(Request $request, Survey $survey)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'service_category_id' => 'sometimes|nullable|exists:service__categories,service_category_id',
            'product_category_id' => 'sometimes|nullable|exists:product__categories,product_category_id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $survey->update($validator->validated());

        return response()->json($survey);
    }

    /**
     * Löscht eine Umfrage.
     * DELETE /api/surveys/{survey}
     */
    public function destroy(Survey $survey)
    {
        $survey->delete();
        return response()->json(null, 204); // 204 No Content
    }
}
