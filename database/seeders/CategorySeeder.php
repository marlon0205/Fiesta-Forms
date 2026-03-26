<?php

namespace Database\Seeders;

use App\Models\Product_Categories;
use App\Models\Service_Categories;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Service Categories
        $services = ['HR & Culture', 'Service Satisfaction', 'Event Registration'];
        foreach ($services as $service) {
            Service_Categories::create(['name' => $service]);
        }

        // Product Categories
        $products = ['Product Feedback', 'IT Support'];
        foreach ($products as $product) {
            Product_Categories::create(['name' => $product]);
        }

        // 5 random words for categories
        Product_Categories::factory(5)->create();
        Service_Categories::factory(5)->create();
    }
}
