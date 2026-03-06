<?php

namespace Database\Factories;

use App\Models\Service_Categories;
use Illuminate\Database\Eloquent\Factories\Factory;

// KORREKTUR: Klassenname an Model angepasst
class Service_CategoriesFactory extends Factory
{
    protected $model = Service_Categories::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
        ];
    }
}
