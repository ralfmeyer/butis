#!/bin/bash
set -euo pipefail

echo "Minimum Clear-Cache Version 1.1"

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
