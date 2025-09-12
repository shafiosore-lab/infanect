@echo off
echo Final database cleanup and setup...

echo.
echo ============================================
echo 1. Stopping all PHP processes
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
echo 3. Removing ALL problematic migration files
echo ============================================
if not exist "database\migrations\backup" mkdir "database\migrations\backup"

REM Remove specific problematic files
for %%f in (
    "database\migrations\*2025_01_15*"
    "database\migrations\*2025_09_01*"
    "database\migrations\*add_role_to_users*"
    "database\migrations\*ensure_users_table*"
    "database\migrations\*fix_users_table*"
    "database\migrations\*create_roles_table*"
) do (
    if exist "%%f" (
        move "%%f" "database\migrations\backup\" 2>nul
        echo Moved %%f to backup
    )
)

echo.
echo ============================================
echo 4. Wiping database completely
echo ============================================
php artisan db:wipe --force

echo.
echo ============================================
echo 5. Running fresh migrations (only Laravel defaults)
echo ============================================
php artisan migrate:fresh --force

echo.
echo ============================================
echo 6. Seeding database
echo ============================================
php artisan db:seed --force

echo.
echo ============================================
echo 7. Final optimizations
echo ============================================
php artisan config:cache
php artisan route:cache
php artisan storage:link

echo.
echo ============================================
echo SUCCESS! Database setup completed!
echo ============================================
echo.
echo 🎉 Your Infanect application is ready!
echo.
echo 🔑 Test accounts:
echo    - Super Admin: admin@infanect.com / password
echo    - Professional Provider: provider@infanect.com / password
echo    - Bonding Provider: bonding@infanect.com / password
echo    - Test Client: client@infanect.com / password
echo.
echo 🌐 Starting server at http://127.0.0.1:8000
start /b php artisan serve
timeout 2 > nul
start http://127.0.0.1:8000
pause
