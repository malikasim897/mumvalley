#!/bin/bash
set -e

echo "Deploying application ..."

# Enter maintenance mode
(php artisan down --message 'The app is being (quickly!) updated. Please try again in a minute.') || true
    # Update codebase
    git checkout master
    git reset --hard origin/master
    git pull origin master
    
    # Install dependencies based on lock file
    composer2 install --no-interaction --prefer-dist --optimize-autoloader
    
    # Migrate database
    php artisan migrate --force

    php artisan config:cache
    php artisan cache:clear
    php artisan optimize:clear


# Exit maintenance mode
php artisan up

echo "Application deployed!"