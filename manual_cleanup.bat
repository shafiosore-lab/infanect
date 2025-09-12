@echo off
echo Manual cleanup of problematic migration files...

echo.
echo ============================================
echo Manually removing specific migration files
echo ============================================

REM Delete specific problematic migration files
if exist "database\migrations\2025_01_15_000002_ensure_users_table_structure.php" (
    del "database\migrations\2025_01_15_000002_ensure_users_table_structure.php"
    echo Deleted: 2025_01_15_000002_ensure_users_table_structure.php
)

if exist "database\migrations\2025_09_01_133130_add_role_to_users_table.php" (
    del "database\migrations\2025_09_01_133130_add_role_to_users_table.php"
    echo Deleted: 2025_09_01_133130_add_role_to_users_table.php
)

if exist "database\migrations\2025_09_01_133001_create_roles_table.php" (
    del "database\migrations\2025_09_01_133001_create_roles_table.php"
    echo Deleted: 2025_09_01_133001_create_roles_table.php
)

REM Remove any other 2025 migration files
for /f %%i in ('dir /b database\migrations\2025*.php 2^>nul') do (
    del "database\migrations\%%i"
    echo Deleted: %%i
)

echo.
echo ============================================
echo Running clean setup after file removal
echo ============================================
call clean_and_setup.bat
