<?php

namespace Database\Seeders;

use App\Models\Meal;
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
         // Create a user with only telegram_id
        $user = User::create([
            'telegram_id' => '12345',
        ]);

        // Seed some meals for this user
        Meal::create([
            'user_id' => $user->id,
            'meal_name' => 'Breakfast',
            'calories' => 300,
            'created_at' => now(),
        ]);

        Meal::create([
            'user_id' => $user->id,
            'meal_name' => 'Lunch',
            'calories' => 600,
            'created_at' => now(),
        ]);
    

    }
}
