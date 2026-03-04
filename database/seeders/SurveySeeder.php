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
        // Wir holen uns den ersten User (Admin/Alex Jensen) als Ersteller
        $user = User::first();

        if (!$user) {
            // Fallback, falls kein User existiert
            $user = User::factory()->create([
                'name' => 'Alex Jensen',
                'email' => 'alex@example.com',
            ]);
        }

        // Kategorien laden
        $productCat = Product_Categories::where('name', 'Product Feedback')->first();
        $hrCat = Service_Categories::where('name', 'HR & Culture')->first();
        $itCat = Product_Categories::where('name', 'IT Support')->first();

        // 1. Umfrage: Q3 Product Roadmap
        $survey1 = Survey::create([
            'title' => 'Q3 Product Roadmap',
            'description' => 'Help us prioritize features for the upcoming quarter.',
            'user_id' => $user->user_id,
            'product_category_id' => $productCat?->product_category_id,
            'is_active' => true,
            'duration_days' => 30
        ]);

        $q1 = $survey1->questions()->create(['question_text' => 'Which feature is most critical for you?']);
        $q1->answerOptions()->createMany([
            ['option_text' => 'Dark Mode'],
            ['option_text' => 'API Access'],
            ['option_text' => 'Mobile App']
        ]);

        $q2 = $survey1->questions()->create(['question_text' => 'How satisfied are you with the current speed?']);
        $q2->answerOptions()->createMany([
            ['option_text' => 'Very Satisfied'],
            ['option_text' => 'Neutral'],
            ['option_text' => 'Dissatisfied']
        ]);

        // 2. Umfrage: Cafeteria Menu (HR)
        $survey2 = Survey::create([
            'title' => 'Cafeteria Menu Update',
            'description' => 'Voting for new vendor options for next month.',
            'user_id' => $user->user_id,
            'service_category_id' => $hrCat?->service_category_id,
            'is_active' => true,
            'duration_days' => 14
        ]);

        $q3 = $survey2->questions()->create(['question_text' => 'Preferred Cuisine?']);
        $q3->answerOptions()->createMany([
            ['option_text' => 'Italian'],
            ['option_text' => 'Asian'],
            ['option_text' => 'Vegan/Healthy']
        ]);

        // 3. Umfrage: IT Ticket Experience (IT)
        $survey3 = Survey::create([
            'title' => 'IT Ticket Experience',
            'description' => 'Rate your recent helpdesk support quality.',
            'user_id' => $user->user_id,
            'product_category_id' => $itCat?->product_category_id,
            'is_active' => true,
            'duration_days' => 90
        ]);

        $q4 = $survey3->questions()->create(['question_text' => 'Was your issue resolved?']);
        $q4->answerOptions()->createMany([
            ['option_text' => 'Yes, quickly'],
            ['option_text' => 'Yes, but took time'],
            ['option_text' => 'No']
        ]);

        // 3. Umfrage: IT Ticket Experience (IT)
        $survey4 = Survey::create([
            'title' => 'Hasst du auch Berufsschule?',
            'description' => 'Selbsterklärend.',
            'user_id' => $user->user_id,
            'product_category_id' => $itCat?->product_category_id,
            'is_active' => true,
            'duration_days' => 90
        ]);

        $q5 = $survey4->questions()->create(['question_text' => 'Was your issue resolved?']);
        $q5->answerOptions()->createMany([
            ['option_text' => 'Yes, quickly'],
            ['option_text' => 'Yes, but took time'],
            ['option_text' => 'No']
        ]);

        // 3. Umfrage: IT Ticket Experience (IT)
        $survey5 = Survey::create([
            'title' => 'Hasst du auch Berufsschule?',
            'description' => 'Selbsterklärend.',
            'user_id' => $user->user_id,
            'product_category_id' => $itCat?->product_category_id,
            'is_active' => true,
            'duration_days' => 90
        ]);

        $q6 = $survey5->questions()->create(['question_text' => 'Was your issue resolved?']);
        $q6->answerOptions()->createMany([
            ['option_text' => 'Yes, quickly'],
            ['option_text' => 'Yes, but took time'],
            ['option_text' => 'No']
        ]);
    }
}
