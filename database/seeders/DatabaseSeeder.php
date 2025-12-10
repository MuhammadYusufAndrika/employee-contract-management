<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contract;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user for login
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Password: password
        ]);

        // Create additional test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Password: password
        ]);

        // Create sample contracts
        // Active contracts
        Contract::factory()->count(20)->create();

        // Contracts expiring soon (within 30 days)
        Contract::factory()->count(8)->expiringSoon()->create();

        // Expired contracts
        Contract::factory()->count(5)->expired()->create();
    }
}
