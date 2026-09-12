#!/bin/sh
set -e

# Ensure Laravel always uses the current environment/config files on container startup.
# This prevents stale cached configuration (especially session.php changes) from
# surviving into a fresh Render deploy.
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache

php artisan migrate --force

exec apache2-foreground
