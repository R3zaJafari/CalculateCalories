FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring exif pcntl bcmath

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project files
COPY . .

# Install dependencies if composer.json exists
RUN if [ -f composer.json ]; then composer install --no-interaction --prefer-dist; fi

# Copy .env.example if .env doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Generate app key (safe to rerun)
RUN php artisan key:generate || true

# Run migrations (ignore if DB not ready)
RUN php artisan migrate || true

# Set permissions
RUN chown -R www-data:www-data /var/www

EXPOSE 8080

# Start the Laravel built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
