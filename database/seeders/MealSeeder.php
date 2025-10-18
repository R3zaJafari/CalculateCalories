<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::where('telegram_id', '12345')->first();
        $user2 = User::where('telegram_id', '99999')->first();

        // Create meals for user1
        Meal::create([
            'user_id' => $user1->id,
            'meal_name' => 'Breakfast',
            'calories' => 400,
            'created_at' => now(),
        ]);

        Meal::create([
            'user_id' => $user1->id,
            'meal_name' => 'Lunch',
            'calories' => 600,
            'created_at' => now(),
        ]);

        // Create meals for user2
        Meal::create([
            'user_id' => $user2->id,
            'meal_name' => 'Breakfast',
            'calories' => 300,
            'created_at' => now(),
        ]);
    
    }
}
