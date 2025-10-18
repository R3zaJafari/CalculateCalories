# CalculateCalories

A simple Laravel API for tracking meals and calculating daily/weekly calories.

## Features

- Register meals via API
- View daily calorie summary
- View weekly stats
- Validation for invalid input
- Fully Dockerized for easy setup
- Automated tests using PHPUnit

## Assumptions

Please see [ASSUMPTIONS.md](ASSUMPTIONS.md) for the assumptions made during development.

## Requirements

- Docker & Docker Compose
- PHP 8.x
- MySQL
- Composer

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/R3zaJafari/CalculateCalories
    ```
   `cd calculate-calories`
2. Copy the example environment file:
    ```bash
    cp .env.example .env
    ```
3. Update .env with your database credentials.

4. Build and start Docker containers:
    ```bash
    docker-compose up -d --build
    ```
5. Run database migrations:
    ```bash
    docker-compose exec app php artisan migrate
    ```
## Usage

### Register a Meal

**POST /api/simulate/register-meal**

**Request Body (JSON):**
```json
{
  "telegram_id": "12345",
  "meal_name": "Lunch",
  "calories": 600
}

curl -X POST http://localhost:8000/api/simulate/register-meal \
-H "Content-Type: application/json" \
-d '{"telegram_id":"12345","meal_name":"Lunch","calories":600}'

{
  "message": "Meal registered successfully",
  "meal": {
    "meal_name": "Lunch",
    "calories": 600,
    "created_at": "2025-10-18 17:09:02"
  }
}
```
### Daily Summary

**GET /api/simulate/daily-summary**

**Query Parameter:**
- `telegram_id` (required) – User's Telegram ID

**Example Response:**
```json
{
    "date": "2025-10-18",
    "total_calories": 4000,
    "meals": [
        {
            "meal_name": "Breakfast",
            "calories": 2000,
            "created_at": "2025-10-18T17:08:26.000000Z"
        },
        {
            "meal_name": "Breakfast", 
            "calories": 2000,
            "created_at": "2025-10-18T17:09:02.000000Z"
        }
    ]
}
```
### Weekly Stats

**GET /api/simulate/stats**

**Query Parameter:**
- `telegram_id` (required) – User's Telegram ID
**Successful Response (200 OK):**
```json
{
  "user": "12345",
  "weekly_stats": [
    {
      "date": "2025-10-18",
      "total_calories": 1600,
      "meals": [
        {
          "meal_name": "Breakfast",
          "calories": 400,
          "created_at": "2025-10-18T08:00:00Z"
        },
        {
          "meal_name": "Lunch",
          "calories": 600,
          "created_at": "2025-10-18T13:00:00Z"
        },
        {
          "meal_name": "Dinner",
          "calories": 600,
          "created_at": "2025-10-18T19:00:00Z"
        }
      ]
    }
  ]
}

