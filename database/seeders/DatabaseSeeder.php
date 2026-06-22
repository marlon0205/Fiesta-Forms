<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.de',
            'password' => bcrypt('admin'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@customer.de',
            'password' => bcrypt('customer'),
            'email_verified_at' => now(),
        ]);
        $customer->assignRole('customer');

        $guest = User::create([
            'name' => 'Guest',
            'email' => 'guest@guest.de',
            'password' => bcrypt('guest'),
            'email_verified_at' => now(),
        ]);
        $guest->assignRole('guest');

        $this->call([
            CategorySeeder::class,
            RewardSeeder::class,
            SurveySeeder::class,
            VoteSeeder::class,
        ]);

    }
}
