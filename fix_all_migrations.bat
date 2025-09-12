@echo off
echo Fixing all migration conflicts and Blade template errors...

echo.
echo ============================================
echo 1. Stopping any running processes
echo ============================================
taskkill /f /im php.exe 2>nul

echo.
echo ============================================
echo 2. Clearing all caches and fixing view errors
echo ============================================
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

REM Clear view compiled files specifically to fix Blade template errors
if exist "storage\framework\views\*" del "storage\framework\views\*" /q 2>nul
echo Cleared compiled view files

REM Clear bootstrap cache files
if exist "bootstrap\cache\compiled.php" del "bootstrap\cache\compiled.php" 2>nul
if exist "bootstrap\cache\config.php" del "bootstrap\cache\config.php" 2>nul
if exist "bootstrap\cache\routes.php" del "bootstrap\cache\routes.php" 2>nul
if exist "bootstrap\cache\services.php" del "bootstrap\cache\services.php" 2>nul
echo Cleared bootstrap cache files

echo.
echo ============================================
echo 3. Backing up and removing conflicting migrations
echo ============================================
if not exist "database\migrations\backup" mkdir "database\migrations\backup"

REM Remove specific problematic migration files
if exist "database\migrations\2025_09_09_140744_add_is_approved_to_activities_table.php" (
    move "database\migrations\2025_09_09_140744_add_is_approved_to_activities_table.php" "database\migrations\backup\"
    echo Moved activities migration to backup
)

REM Move all conflicting migration files to backup
for %%f in (
    "database\migrations\*create_roles_table*"
    "database\migrations\*add_role_to_users_table*"
    "database\migrations\2025_09_01_*"
    "database\migrations\*2025_01_15*"
    "database\migrations\*ensure_users*"
    "database\migrations\*fix_users*"
    "database\migrations\*update_users*"
    "database\migrations\*modify_users*"
    "database\migrations\*providers*"
    "database\migrations\*role_id*"
) do (
    if exist "%%f" (
        move "%%f" "database\migrations\backup\" 2>nul
        echo Moved %%f to backup
    )
)

echo.
echo ============================================
echo 4. Dropping and recreating database
echo ============================================
php artisan db:wipe --force
if errorlevel 1 (
    echo ERROR: Database wipe failed!
    pause
    exit /b 1
)
echo Database wiped successfully

echo.
echo ============================================
echo 5. Running fresh migrations (Laravel defaults only)
echo ============================================
php artisan migrate:fresh --force
if errorlevel 1 (
    echo ERROR: Migration failed!
    echo Checking remaining migration files:
    dir /b "database\migrations\*.php" 2>nul
    pause
    exit /b 1
)
echo Fresh migrations completed successfully

echo.
echo ============================================
echo 6. Seeding database with complete setup
echo ============================================
php artisan db:seed --force
if errorlevel 1 (
    echo ERROR: Database seeding failed!
    pause
    exit /b 1
)
echo Database seeding completed successfully

echo.
echo ============================================
echo 7. Final optimizations
echo ============================================
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
echo Application optimized

echo.
echo ============================================
echo SUCCESS! Setup completed!
echo ============================================
echo.
echo 🎉 Your Infanect application is ready!
echo.
echo 🔑 Test accounts available:
echo    - Super Admin: admin@infanect.com / password
echo    - Professional Provider: provider@infanect.com / password
echo    - Bonding Provider: bonding@infanect.com / password
echo    - Test Client: client@infanect.com / password
echo    - Test Manager: manager@infanect.com / password
echo.
echo 🌐 Starting server at http://127.0.0.1:8000
start /b php artisan serve
timeout 2 > nul
start http://127.0.0.1:8000
echo Server started successfully
pause
