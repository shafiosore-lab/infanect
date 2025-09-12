@echo off
echo Starting Laravel setup process...

echo.
echo ============================================
echo 1. Clearing all caches and configurations
echo ============================================
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

echo.
echo ============================================
echo 2. Running database migrations
echo ============================================
php artisan migrate:fresh --force

echo.
echo ============================================
echo 3. Running database seeders
echo ============================================
php artisan db:seed --force

echo.
echo ============================================
echo 4. Creating storage links
echo ============================================
php artisan storage:link

echo.
echo ============================================
echo 5. Optimizing application
echo ============================================
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo.
echo ============================================
echo Setup completed successfully!
echo ============================================
echo Your application should now be ready to use.
echo Visit: http://127.0.0.1:8000
echo.
pause
