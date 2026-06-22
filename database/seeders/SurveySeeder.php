<?php

namespace Database\Seeders;

use App\Models\Product_Categories;
use App\Models\Service_Categories;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first();

        $serviceCategoryIds = Service_Categories::query()->pluck('service_category_id')->all();
        $productCategoryIds = Product_Categories::query()->pluck('product_category_id')->all();

        $surveys = [
            ['title' => 'Wie zufrieden sind Sie mit unserem Smart-Home-Hub?', 'description' => 'Bewerten Sie unseren zentralen Hub für Smart-Home-Geräte.', 'days' => 30, 'active' => true],
            ['title' => 'Welche Funktionen wünschen Sie sich für unsere App?', 'description' => 'Teilen Sie uns Ihre Wünsche für die nächste App-Version mit.', 'days' => 60, 'active' => true],
            ['title' => 'Bewertung unserer Smart-Lampen-Serie', 'description' => 'Wie gefällt Ihnen unsere neue LED-Beleuchtungsserie?', 'days' => 45, 'active' => true],
            ['title' => 'Kundenzufriedenheit Lieferservice', 'description' => 'War die Lieferung Ihres Produkts zufriedenstellend?', 'days' => 14, 'active' => false],
            ['title' => 'Smart-Thermostat Nutzererfahrung', 'description' => 'Wie einfach ist die Bedienung unseres Thermostats?', 'days' => 21, 'active' => true],
            ['title' => 'Interesse an Smart-Security-Produkten', 'description' => 'Würden Sie Smart-Sicherheitskameras von uns kaufen?', 'days' => 90, 'active' => true],
            ['title' => 'Bewertung des Kundensupports', 'description' => 'Wie war Ihre Erfahrung mit unserem Support-Team?', 'days' => 30, 'active' => false],
            ['title' => 'Smart-Steckdosen – Alltagstauglichkeit', 'description' => 'Nutzen Sie unsere smarten Steckdosen im Alltag?', 'days' => 60, 'active' => true],
        ];

        $questions = [
            ['text' => 'Wie bewerten Sie die Benutzerfreundlichkeit?', 'options' => ['Sehr gut', 'Gut', 'Befriedigend', 'Schlecht']],
            ['text' => 'Würden Sie das Produkt weiterempfehlen?', 'options' => ['Ja, auf jeden Fall', 'Wahrscheinlich ja', 'Eher nein', 'Nein']],
            ['text' => 'Wie oft nutzen Sie das Produkt?', 'options' => ['Täglich', 'Mehrmals pro Woche', 'Selten', 'Nie']],
            ['text' => 'Wie zufrieden sind Sie mit dem Preis-Leistungs-Verhältnis?', 'options' => ['Sehr zufrieden', 'Zufrieden', 'Neutral', 'Unzufrieden']],
            ['text' => 'Wie war die Installation?', 'options' => ['Sehr einfach', 'Einfach', 'Schwierig', 'Sehr schwierig']],
            ['text' => 'Wie beurteilen Sie die Verarbeitungsqualität?', 'options' => ['Ausgezeichnet', 'Gut', 'Durchschnittlich', 'Mangelhaft']],
        ];

        $svcCount = count($serviceCategoryIds);
        $prodCount = count($productCategoryIds);

        foreach ($surveys as $i => $data) {
            $svcId = $svcCount > 0 && $i % 2 === 0 ? $serviceCategoryIds[$i % $svcCount] : null;
            $prodId = $prodCount > 0 && $i % 2 !== 0 ? $productCategoryIds[$i % $prodCount] : null;
            if (!$svcId && !$prodId && $svcCount > 0) {
                $svcId = $serviceCategoryIds[$i % $svcCount];
            }

            $survey = Survey::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'user_id' => $user->user_id,
                'service_category_id' => $svcId,
                'product_category_id' => $prodId,
                'is_active' => $data['active'],
                'duration_days' => $data['days'],
                'created_at' => now()->subDays($i * 5),
                'updated_at' => now(),
            ]);

            $surveyQuestions = array_slice($questions, 0, ($i % 3) + 2);
            foreach ($surveyQuestions as $q) {
                $question = $survey->questions()->create(['question_text' => $q['text']]);
                foreach ($q['options'] as $opt) {
                    $question->answerOptions()->create(['option_text' => $opt]);
                }
            }
        }
    }
}
