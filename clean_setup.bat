@echo off
echo Cleaning up problematic migrations and starting fresh...

echo.
echo ============================================
echo 1. Stopping any running servers
echo ============================================
taskkill /f /im php.exe 2>nul

echo.
echo ============================================
echo 2. Clearing all caches
echo ============================================
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

echo.
echo ============================================
echo 3. Removing problematic migration files
echo ============================================
if exist "database\migrations\*add_role_to_users_table*" (
    del "database\migrations\*add_role_to_users_table*"
    echo Removed problematic migration files
)

echo.
echo ============================================
echo 4. Fresh database setup
echo ============================================
php artisan migrate:fresh --force

echo.
echo ============================================
echo 5. Seeding database
echo ============================================
php artisan db:seed --force

echo.
echo ============================================
echo 6. Optimizing application
echo ============================================
php artisan config:cache
php artisan route:cache

echo.
echo ============================================
echo 7. Starting development server
echo ============================================
echo Setup completed successfully!
echo.
echo Test accounts available:
echo - Super Admin: admin@infanect.com / password
echo - Professional Provider: provider@infanect.com / password
echo - Bonding Provider: bonding@infanect.com / password
echo - Test Client: client@infanect.com / password
echo.
echo Starting server at http://127.0.0.1:8000
php artisan serve
pause
