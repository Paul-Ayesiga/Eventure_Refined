<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organisation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Run Role seeder first
        $this->call(RoleSeeder::class);

        // Create test organization
        Organisation::create([
            'user_id' => $user->id,
            'name' => 'Test Organization',
            'email' => 'org@example.com',
            'phone_number' => '1234567890',
            'country' => 'United States',
            'currency' => 'USD'
        ]);

        // Run Event seeder
        $this->call(EventSeeder::class);
    }
}
