<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void {
        $this->call(RoleSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.de',
            'password' => bcrypt('admin'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');


        $customer = User::factory()->create([
            'name' => 'Customer',
            'email' => 'customer@customer.de',
            'password' => bcrypt('customer'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $customer->assignRole('customer');


        $guest = User::factory()->create([
            'name' => 'Guest',
            'email' => 'guest@guest.de',
            'password' => bcrypt('guest'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $guest->assignRole('guest');


    }
}
