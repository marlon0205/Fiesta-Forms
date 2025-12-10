<?php

namespace Database\Factories;

use App\Models\Survey;
use App\Models\User;
use App\Models\Service_Categories;
use App\Models\Product_Categories;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyFactory extends Factory
{
    protected $model = Survey::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->sentence(15), // KORREKTUR: Gekürzt, um in varchar(255) zu passen
            'duration_days' => $this->faker->numberBetween(7, 30), // KORREKTUR: Fehlende Spalte hinzugefügt
            'is_active' => true, // KORREKTUR: Fehlende Spalte hinzugefügt
            'user_id' => User::inRandomOrder()->first()->user_id,
            // KORREKTUR: Logik vereinfacht, um not-null zu erfüllen
            'service_category_id' => Service_Categories::inRandomOrder()->first()->service_category_id,
            'product_category_id' => Product_Categories::inRandomOrder()->first()->product_category_id,
        ];
    }
}
