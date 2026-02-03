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
        // "IT Support" packen wir hier rein, damit es verteilt ist, 
        // im Frontend werden eh beide Tabellen gemerged.
        $products = ['Product Feedback', 'IT Support'];
        foreach ($products as $product) {
            Product_Categories::create(['name' => $product]);
        }
    }
}