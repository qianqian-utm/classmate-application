#!/bin/bash

# Wait for database connection
echo "Waiting for database connection..."
while ! php artisan db:monitor --check >/dev/null 2>&1; do
    echo "Still waiting for database connection..."
    sleep 1
done
echo "Database connection established"

# Run migrations and optimization commands
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the application
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}