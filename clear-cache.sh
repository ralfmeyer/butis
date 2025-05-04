#!/bin/bash
set -euo pipefail

echo "Clear-Cache Version 1.0"

# Update dependencies
composer update
npx update-browserslist-db@latest

# Lösche den Vite-Cache vor den Laravel-Befehlen
rm -rf node_modules/.vite
rm storage/laravel.log

# Clear Laravel Caches (zuerst löschen, bevor neu gecached wird)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Erstelle neue Caches (nach dem Löschen neu generieren)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Frontend-Build
npm run build

# Anwendung optimieren (nach Build und Cache-Neuerstellung)
php artisan optimize
