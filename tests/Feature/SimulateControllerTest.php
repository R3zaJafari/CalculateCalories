<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimulateControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_registers_a_meal_and_stores_in_database()
    {
        $response = $this->postJson('/api/simulate/register-meal', [
            'telegram_id' => '12345',
            'meal_name' => 'Lunch',
            'calories' => 600,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Meal registered successfully',
                     'meal' => [
                         'meal_name' => 'Lunch',
                         'calories' => 600,
                     ]
                 ]);

        $this->assertDatabaseHas('meals', [
            'meal_name' => 'Lunch',
            'calories' => 600,
        ]);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_daily_summary_correctly()
    {
        $user = User::create(['telegram_id' => '999']);

        Meal::create([
            'user_id' => $user->id,
            'meal_name' => 'Breakfast',
            'calories' => 400,
            'created_at' => now(),
        ]);

        Meal::create([
            'user_id' => $user->id,
            'meal_name' => 'Lunch',
            'calories' => 600,
            'created_at' => now(),
        ]);

        $response = $this->getJson('/api/simulate/daily-summary?telegram_id=999');

        $response->assertStatus(200)
            ->assertJson([
                'total_calories' => 1000,
            ])
            ->assertJsonStructure([
                'date',
                'total_calories',
                'meals'
            ]);
    }
}
