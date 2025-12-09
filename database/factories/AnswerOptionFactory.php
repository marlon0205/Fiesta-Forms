<?php

namespace Database\Factories;

use App\Models\AnswerOptions;
use App\Models\Questions;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerOptionFactory extends Factory
{
    protected $model = AnswerOptions::class;

    public function definition()
    {
        return [
            'question_id' => Questions::factory(), // Standardmäßig eine neue Frage erstellen
            'option_text' => $this->faker->words(2, true),
        ];
    }
}
