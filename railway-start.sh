#!/bin/sh
# Railway startup script (alternative to Dockerfile CMD)
# This can be used if deploying with Nixpacks instead of Dockerfile

set -e

cd src

# Cache configuration
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run migrations
php artisan migrate --force || true

# Start the application
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}

