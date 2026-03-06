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
        // Get the first user (Admin)
        $user = User::first();

        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
            ]);
        }

        // Load Categories
        $productCat = Product_Categories::where('name', 'Product Feedback')->first() 
            ?? Product_Categories::first();
        $hrCat = Service_Categories::where('name', 'HR & Culture')->first() 
            ?? Service_Categories::first();
        $itCat = Product_Categories::where('name', 'IT Support')->first() 
            ?? Product_Categories::first();

        // 1. Case: Standard Active Survey (Multi-Question)
        $survey1 = Survey::create([
            'title' => 'Q3 Product Roadmap',
            'description' => 'Help us prioritize features for the upcoming quarter. Your feedback directly impacts our development cycle.',
            'user_id' => $user->user_id,
            'product_category_id' => $productCat?->product_category_id,
            'is_active' => true,
            'duration_days' => 30
        ]);

        $q1 = $survey1->questions()->create(['question_text' => 'Which feature is most critical for you?']);
        $q1->answerOptions()->createMany([
            ['option_text' => 'Dark Mode UI'],
            ['option_text' => 'REST API Access'],
            ['option_text' => 'Native Mobile App']
        ]);

        $q2 = $survey1->questions()->create(['question_text' => 'How would you rate the current platform stability?']);
        $q2->answerOptions()->createMany([
            ['option_text' => 'Rock Solid'],
            ['option_text' => 'Minor Issues'],
            ['option_text' => 'Frequent Crashes']
        ]);

        // 2. Case: Inactive Survey (Testing "Results Only" for everyone)
        $survey2 = Survey::create([
            'title' => 'Archived: 2024 Holiday Party',
            'description' => 'Voting for the location of last year\'s holiday event.',
            'user_id' => $user->user_id,
            'service_category_id' => $hrCat?->service_category_id,
            'is_active' => false,
            'duration_days' => 14
        ]);

        $q3 = $survey2->questions()->create(['question_text' => 'Final Venue Choice?']);
        $q3->answerOptions()->createMany([
            ['option_text' => 'Grand Ballroom'],
            ['option_text' => 'Rooftop Lounge'],
            ['option_text' => 'Mountain Retreat']
        ]);

        // 3. Case: Single Question Survey (Quick Poll)
        $survey3 = Survey::create([
            'title' => 'Security Awareness Check',
            'description' => 'A quick check to see if everyone remembers the new 2FA policy.',
            'user_id' => $user->user_id,
            'product_category_id' => $itCat?->product_category_id,
            'is_active' => true,
            'duration_days' => 7
        ]);

        $q4 = $survey3->questions()->create(['question_text' => 'What is the minimum required length for your new password?']);
        $q4->answerOptions()->createMany([
            ['option_text' => '8 Characters'],
            ['option_text' => '12 Characters'],
            ['option_text' => '16 Characters']
        ]);

        // 4. Case: Survey with many options (Testing UI Overflow)
        $survey4 = Survey::create([
            'title' => 'Remote Work Equipment Preferences',
            'description' => 'Select the item you need most for your home office setup.',
            'user_id' => $user->user_id,
            'product_category_id' => $itCat?->product_category_id,
            'is_active' => true,
            'duration_days' => 60
        ]);

        $q5 = $survey4->questions()->create(['question_text' => 'Primary Hardware Request?']);
        $q5->answerOptions()->createMany([
            ['option_text' => 'Ergonomic Chair'],
            ['option_text' => '4K UltraWide Monitor'],
            ['option_text' => 'Mechanical Keyboard'],
            ['option_text' => 'Noise Cancelling Headphones'],
            ['option_text' => 'Standing Desk Converter'],
            ['option_text' => 'High-End WebCam']
        ]);

        // 5. Case: Different Category (HR Service)
        $survey5 = Survey::create([
            'title' => 'Quarterly Team Building',
            'description' => 'Vote for next month\'s team activity.',
            'user_id' => $user->user_id,
            'service_category_id' => $hrCat?->service_category_id,
            'is_active' => true,
            'duration_days' => 21
        ]);

        $q6 = $survey5->questions()->create(['question_text' => 'Activity Preference?']);
        $q6->answerOptions()->createMany([
            ['option_text' => 'Escape Room'],
            ['option_text' => 'Laser Tag'],
            ['option_text' => 'Cooking Class'],
            ['option_text' => 'Hiking Trip']
        ]);
    }
}
