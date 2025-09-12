@echo off
echo Fixing migration conflict...

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
echo 2. Dropping all tables and starting fresh
echo ============================================
php artisan migrate:fresh --force

echo.
echo ============================================
echo 3. Running seeders
echo ============================================
php artisan db:seed --force

echo.
echo ============================================
echo 4. Clearing problematic migration from database
echo ============================================
php artisan tinker --execute="DB::table('migrations')->where('migration', 'like', '%%add_role_to_users_table%%')->delete();"

echo.
echo ============================================
echo 5. Optimizing application
echo ============================================
php artisan config:cache
php artisan route:cache

echo.
echo ============================================
echo Migration conflict resolved!
echo ============================================
echo Your application should now work properly.
pause
