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
        $user = User::query()->first() ?? User::factory()->create([
            'name' => 'Alex Jensen',
            'email' => 'alex@example.com',
        ]);

        $serviceCategoryIds = Service_Categories::query()->pluck('service_category_id')->all();
        $productCategoryIds = Product_Categories::query()->pluck('product_category_id')->all();

        for ($index = 1; $index <= 36; $index++) {
            $serviceCategoryId = null;
            $productCategoryId = null;

            if (! empty($serviceCategoryIds) && fake()->boolean(50)) {
                $serviceCategoryId = fake()->randomElement($serviceCategoryIds);
            }

            if (! empty($productCategoryIds) && fake()->boolean(50)) {
                $productCategoryId = fake()->randomElement($productCategoryIds);
            }

            if (! $serviceCategoryId && ! $productCategoryId) {
                if (! empty($serviceCategoryIds)) {
                    $serviceCategoryId = fake()->randomElement($serviceCategoryIds);
                } elseif (! empty($productCategoryIds)) {
                    $productCategoryId = fake()->randomElement($productCategoryIds);
                }
            }

            $survey = Survey::create([
                'title' => fake()->unique()->sentence(4),
                'description' => fake()->sentence(12),
                'user_id' => $user->user_id,
                'service_category_id' => $serviceCategoryId,
                'product_category_id' => $productCategoryId,
                'is_active' => fake()->boolean(70),
                'duration_days' => fake()->numberBetween(7, 90),
                'created_at' => now()->subDays(fake()->numberBetween(0, 120)),
                'updated_at' => now(),
            ]);

            $questionCount = fake()->numberBetween(2, 6);

            for ($questionIndex = 1; $questionIndex <= $questionCount; $questionIndex++) {
                $question = $survey->questions()->create([
                    'question_text' => fake()->sentence(8),
                ]);

                $optionCount = fake()->numberBetween(2, 5);

                for ($optionIndex = 1; $optionIndex <= $optionCount; $optionIndex++) {
                    $question->answerOptions()->create([
                        'option_text' => fake()->words(fake()->numberBetween(1, 4), true),
                    ]);
                }
            }
        }

        fake()->unique(true);
    }
}
