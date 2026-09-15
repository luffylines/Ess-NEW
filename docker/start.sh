#!/bin/sh
set -e

# Run migrations first so database-backed sessions are available before Laravel starts.
php artisan migrate --force

# Make local public-disk uploads reachable when Cloudinary is not configured.
# Ignore the error when the link already exists.
php artisan storage:link >/dev/null 2>&1 || true

# Clear stale caches and rebuild the configuration using the current environment.
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache

exec apache2-foreground
