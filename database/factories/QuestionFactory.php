<?php

namespace Database\Factories;

use App\Models\Questions;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Questions::class;

    public function definition()
    {
        return [
            'survey_id' => Survey::factory(), // Standardmäßig eine neue Umfrage erstellen
            'question_text' => $this->faker->sentence . '?',
            'question_type' => $this->faker->randomElement(['multiple_choice', 'single_choice', 'text']),
        ];
    }
}
