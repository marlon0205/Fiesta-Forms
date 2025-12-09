<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product_Categories;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Erstelle 5 Produkt-Kategorien
        Product_Categories::factory(5)->create();
    }
}
