<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service_Categories;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Erstelle 5 Service-Kategorien
        Service_Categories::factory(5)->create();
    }
}
