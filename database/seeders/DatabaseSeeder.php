<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Rollen und spezifische User erstellen
        $this->call(RoleSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.de',
            'password' => bcrypt('admin'),
        ]);
        $admin->assignRole('admin');

        $customer = User::factory()->create([
            'name' => 'Customer',
            'email' => 'customer@customer.de',
            'password' => bcrypt('customer'),
        ]);
        $customer->assignRole('customer');

        $guest = User::factory()->create([
            'name' => 'Guest',
            'email' => 'guest@guest.de',
            'password' => bcrypt('guest'),
        ]);
        $guest->assignRole('guest');

        // 2. Zusätzliche zufällige User
        User::factory(10)->create();

        $this->call(CategorySeeder::class);
        $this->call(SurveySeeder::class);
        $this->call(VoteSeeder::class);

        // 3. Kategorien
        $this->call([
            ServiceCategorySeeder::class,
            ProductCategorySeeder::class,
        ]);

        // 4. Umfragen mit Fragen und Antworten
        //$this->call(SurveySeeder::class);
    }
}
