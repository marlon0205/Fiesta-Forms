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
        // Wir erstellen ein paar Dummy-User für die Votes
        $voters = User::factory()->count(20)->create();

        $surveys = Survey::with('questions.answerOptions')->get();

        foreach ($surveys as $survey) {
            // Zufällige Anzahl an Votes pro Umfrage (zwischen 5 und 15)
            $voteCount = rand(5, 15);

            // Wir nehmen zufällige User aus unserem Pool
            $randomVoters = $voters->random($voteCount);

            foreach ($randomVoters as $voter) {
                // 1. Die Teilnahme (Vote) erstellen
                $vote = Votes::create([
                    'survey_id' => $survey->survey_id,
                    'user_id' => $voter->user_id,
                    // Zufälliges Datum in den letzten 30 Tagen für schöne Charts
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);

                // 2. Für jede Frage der Umfrage eine Antwort generieren
                foreach ($survey->questions as $question) {
                    // Zufällige Option auswählen
                    $randomOption = $question->answerOptions->random();

                    // Antwort speichern
                    $vote->answers()->create([
                        'question_id' => $question->question_id,
                        'option_id' => $randomOption->option_id,
                    ]);
                }
            }
        }
    }
}
