@echo off
echo Starting PHP Development Server...
echo.

REM Check if XAMPP PHP exists
if not exist "C:\xampp\php\php.exe" (
    echo Error: PHP not found in XAMPP directory
    echo Please install XAMPP or correct the PHP path
    pause
    exit /b 1
)

REM Set project root directory
set PROJECT_ROOT=%~dp0
cd "%PROJECT_ROOT%"

echo Current directory: %CD%
echo Server starting at http://localhost:8000
echo Press Ctrl+C to stop the server
echo.

REM Start PHP server from project root, serving from public directory
"C:\xampp\php\php.exe" -S localhost:8000 -t "%PROJECT_ROOT%\public" 

pause