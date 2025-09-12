@echo off
echo Resetting database completely...

echo.
echo ============================================
echo 1. Stopping any running processes
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
echo 3. Dropping all tables and starting fresh
echo ============================================
php artisan db:wipe --force

echo.
echo ============================================
echo 4. Running fresh migrations
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
echo Database reset completed successfully!
echo ============================================
echo.
echo Test accounts available:
echo - Super Admin: admin@infanect.com / password
echo - Professional Provider: provider@infanect.com / password
echo - Bonding Provider: bonding@infanect.com / password
echo - Test Client: client@infanect.com / password
echo.
echo Starting server...
start /b php artisan serve
echo Server started at http://127.0.0.1:8000
pause
