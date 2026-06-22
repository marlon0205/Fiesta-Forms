<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\User;
use App\Models\Votes;
use Illuminate\Database\Seeder;

class VoteSeeder extends Seeder
{
    public function run(): void
    {
        $voterData = [
            ['name' => 'Max Mustermann', 'email' => 'max@example.de'],
            ['name' => 'Anna Schmidt', 'email' => 'anna@example.de'],
            ['name' => 'Tom Weber', 'email' => 'tom@example.de'],
            ['name' => 'Lisa Bauer', 'email' => 'lisa@example.de'],
            ['name' => 'Felix König', 'email' => 'felix@example.de'],
            ['name' => 'Sarah Müller', 'email' => 'sarah@example.de'],
            ['name' => 'Jan Fischer', 'email' => 'jan@example.de'],
            ['name' => 'Nina Hoffmann', 'email' => 'nina@example.de'],
        ];

        $voters = collect();
        foreach ($voterData as $data) {
            $voter = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $voter->assignRole('customer');
            $voters->push($voter);
        }

        $surveys = Survey::with('questions.answerOptions')->get();

        foreach ($surveys as $si => $survey) {
            $voteCount = min(($si % 5) + 3, $voters->count());
            $randomVoters = $voters->slice($si % $voters->count(), $voteCount)->values();

            foreach ($randomVoters as $voter) {
                if ($survey->questions->isEmpty()) continue;

                $vote = Votes::create([
                    'survey_id' => $survey->survey_id,
                    'user_id' => $voter->user_id,
                    'created_at' => now()->subDays($si * 2),
                ]);

                foreach ($survey->questions as $qi => $question) {
                    if ($question->answerOptions->isEmpty()) continue;
                    $option = $question->answerOptions->values()->get($qi % $question->answerOptions->count());
                    $vote->answers()->create([
                        'question_id' => $question->question_id,
                        'option_id' => $option->option_id,
                    ]);
                }

                $voter->increment('points', $survey->questions->count() * 5);
            }
        }
    }
}
