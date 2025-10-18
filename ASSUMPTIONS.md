# Assumptions

1. A `User` is uniquely identified by `telegram_id`.
2. Meal names are strings and calories are integers.
3. Daily summary calculates calories per **calendar day** (00:00–23:59).
4. Weekly stats cover the last 7 days from the current date.
5. Invalid input (missing fields, negative calories) returns HTTP 400.
6. System runs entirely in Docker for consistent environment.
7. SQLite can be used for local testing, MySQL in Docker for full stack.
