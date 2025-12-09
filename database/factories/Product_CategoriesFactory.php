<?php

namespace Database\Factories;

use App\Models\Product_Categories;
use Illuminate\Database\Eloquent\Factories\Factory;

// KORREKTUR: Klassenname an Model angepasst
class Product_CategoriesFactory extends Factory
{
    protected $model = Product_Categories::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            // KORREKTUR: 'description' entfernt, da Spalte nicht existiert
        ];
    }
}
