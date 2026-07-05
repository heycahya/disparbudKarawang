#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

echo "============================================="
echo "Starting Production Deployment & Optimization"
echo "============================================="

# 1. Environment Caching & Optimizations
echo "--> Optimizing Laravel caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 2. Compile Frontend Assets
echo "--> Installing Node packages and building assets..."
if [ -f "package-lock.json" ]; then
    npm ci
else
    npm install
fi
npm run build

# 3. Database Migration
echo "--> Running database migrations..."
php artisan migrate --force

# 4. Storage Link
echo "--> Ensuring storage symbolic link is created..."
php artisan storage:link

echo "============================================="
echo "Deployment Ready & System Optimized Successfully!"
echo "============================================="
