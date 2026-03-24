<?php

namespace Database\Factories;

use App\Models\Questions;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

// KORREKTUR: Klassenname an Model angepasst
class QuestionsFactory extends Factory
{
    protected $model = Questions::class;

    public function definition()
    {
        return [
            'survey_id' => Survey::factory(),
            'question_text' => $this->faker->sentence . '?',
        ];
    }
}
