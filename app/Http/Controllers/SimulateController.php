<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Meal;

class SimulateController extends Controller
{
    public function registerMeal(Request $request)
    {
        try {
        $validated = $request->validate([
            'telegram_id' => 'required',
            'meal_name' => 'required|string|max:255',
            'calories' => 'required|numeric|min:1',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Invalid input',
                'details' => $e->errors(),
            ], 400);
        }

        // Find user or create if not exists
        $user = User::firstOrCreate(['telegram_id' => $validated['telegram_id']]);

        $meal = Meal::create([
            'user_id' => $user->id,
            'meal_name' => $validated['meal_name'],
            'calories' => $validated['calories'],
        ]);

        // Return confirmation JSON
        return response()->json([
            'message' => 'Meal registered successfully',
            'meal' => [
                'meal_name' => $meal->meal_name,
                'calories' => $meal->calories,
                'created_at' => $meal->created_at->format('Y-m-d H:i:s'),
            ],
        ], 200);
    }


    public function dailySummary(Request $request)
    {
        $telegramId = $request->query('telegram_id');

        // Validate presence of telegram_id
        if (!$telegramId) {
            return response()->json(['error' => 'telegram_id is required'], 400);
        }

        // Find the user
        $user = User::where('telegram_id', $telegramId)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 400);
        }

        // Get today's meals
        $today = now()->startOfDay(); // today 00:00:00
        $meals = $user->meals()
            ->where('created_at', '>=', $today)
            ->get(['meal_name', 'calories', 'created_at']);

        // Calculate total calories
        $totalCalories = $meals->sum('calories');

        // Return response
        return response()->json([
            'date' => now()->toDateString(),
            'total_calories' => $totalCalories,
            'meals' => $meals,
        ]);
    }


    public function weeklyStats(Request $request)
    {
        $telegramId = $request->query('telegram_id');

        // Validate presence of telegram_id
        if (!$telegramId) {
            return response()->json(['error' => 'telegram_id is required'], 400);
        }

        // Find the user
        $user = User::where('telegram_id', $telegramId)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 400);
        }

        // Start of the current week (Monday)
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday 00:00
        $endOfWeek = Carbon::now()->endOfWeek();     // Sunday 23:59:59

        // Get meals for the current week
        $meals = $user->meals()
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->get(['meal_name', 'calories', 'created_at']);

        // Group meals by date
        $weeklyStats = $meals->groupBy(function ($meal) {
            return $meal->created_at->toDateString();
        })->map(function ($dayMeals, $date) {
            return [
                'date' => $date,
                'total_calories' => $dayMeals->sum('calories'),
                'meals' => $dayMeals->map(function ($meal) {
                    return [
                        'meal_name' => $meal->meal_name,
                        'calories' => $meal->calories,
                        'created_at' => $meal->created_at->toDateTimeString(),
                    ];
                })->values(),
            ];
        })->values();

        // Return response
        return response()->json([
            'user' => $user->telegram_id, 
            'weekly_stats' => $weeklyStats,
        ]);
    }

}
