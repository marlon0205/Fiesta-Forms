<?php

namespace Database\Seeders;

use App\Models\AnswerOptions;
use App\Models\Questions;
use App\Models\Survey;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Survey::factory(20)
            ->has(
                Questions::factory(5)
                    ->has(AnswerOptions::factory(4), 'answerOptions')
            )
            ->create();
    }
}
