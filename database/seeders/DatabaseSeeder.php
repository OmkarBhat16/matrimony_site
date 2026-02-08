<?php

namespace Database\Seeders;

use App\Models\User;
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
        // User::factory(10)->create();

        // Generate 10 random user profiles (creates associated users automatically)
        \App\Models\UserProfile::factory(10)->create();

        User::factory()->create([
            'name' => 'tester',
            'username' => 'tester',
            'email' => 'test@example.com',
            'password' => bcrypt('qwerty123'),
            'approved' => true,
            'role' => 'user',
        ]);

        User::factory()->create( [
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('qwerty123'),
            'approved' => true,
            'role' => 'admin',  
        ]);
    }
}
