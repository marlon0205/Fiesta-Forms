<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
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

        $this->call([
            CategorySeeder::class,
            SurveySeeder::class,
            VoteSeeder::class,
        ]);
    }
}
