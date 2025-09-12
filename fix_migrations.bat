@echo off
echo Fixing migration issues...

echo.
echo ============================================
echo 1. Clearing all caches
echo ============================================
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo.
echo ============================================
echo 2. Rolling back problematic migrations
echo ============================================
php artisan migrate:rollback --step=1

echo.
echo ============================================
echo 3. Running fresh migrations
echo ============================================
php artisan migrate:fresh --force

echo.
echo ============================================
echo 4. Running seeders
echo ============================================
php artisan db:seed --force

echo.
echo ============================================
echo 5. Optimizing application
echo ============================================
php artisan config:cache
php artisan route:cache

echo.
echo ============================================
echo Migration fix completed!
echo ============================================
pause
