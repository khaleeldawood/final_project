# UniHub Laravel - Quick Setup Verification Script
# This script checks if everything is ready to run

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "UniHub Laravel - Setup Verification" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check PHP
Write-Host "[1/6] Checking PHP..." -ForegroundColor Yellow
try {
    $phpVersion = php --version 2>&1 | Select-Object -First 1
    Write-Host "✓ PHP is installed: $phpVersion" -ForegroundColor Green
} catch {
    Write-Host "✗ PHP is not installed or not in PATH" -ForegroundColor Red
    exit 1
}

# Check Composer
Write-Host "`n[2/6] Checking Composer..." -ForegroundColor Yellow
try {
    $composerVersion = composer --version 2>&1 | Select-Object -First 1
    Write-Host "✓ Composer is installed: $composerVersion" -ForegroundColor Green
} catch {
    Write-Host "✗ Composer is not installed or not in PATH" -ForegroundColor Red
    exit 1
}

# Check Node.js
Write-Host "`n[3/6] Checking Node.js..." -ForegroundColor Yellow
try {
    $nodeVersion = node --version 2>&1
    Write-Host "✓ Node.js is installed: $nodeVersion" -ForegroundColor Green
} catch {
    Write-Host "✗ Node.js is not installed or not in PATH" -ForegroundColor Red
    Write-Host "  Install from: https://nodejs.org/" -ForegroundColor Yellow
    exit 1
}

# Check if vendor directory exists
Write-Host "`n[4/6] Checking Laravel dependencies..." -ForegroundColor Yellow
if (Test-Path "./vendor") {
    Write-Host "✓ Vendor directory exists (Composer dependencies installed)" -ForegroundColor Green
} else {
    Write-Host "✗ Vendor directory not found. Run: composer install" -ForegroundColor Red
    exit 1
}

# Check if node_modules exists
Write-Host "`n[5/6] Checking Node dependencies..." -ForegroundColor Yellow
if (Test-Path "./node_modules") {
    Write-Host "✓ node_modules directory exists (NPM dependencies installed)" -ForegroundColor Green
} else {
    Write-Host "⚠ node_modules directory not found. Run: npm install" -ForegroundColor Yellow
}

# Test database connection using Laravel
Write-Host "`n[6/6] Testing database connection..." -ForegroundColor Yellow
$dbTest = php artisan db:show 2>&1
if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Database connection successful!" -ForegroundColor Green
} else {
    Write-Host "✗ Database connection failed!" -ForegroundColor Red
    Write-Host "  Make sure XAMPP MySQL is running" -ForegroundColor Yellow
    Write-Host "  Database: unihub" -ForegroundColor Yellow
    Write-Host "  Run: php artisan migrate (if database is empty)" -ForegroundColor Yellow
}

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "Setup Verification Complete!" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "`nNext Steps:" -ForegroundColor Yellow
Write-Host "1. Make sure XAMPP is running with MySQL started" -ForegroundColor White
Write-Host "2. Create database 'unihub' if not exists" -ForegroundColor White
Write-Host "3. Run: php artisan migrate" -ForegroundColor White
Write-Host "4. Run: php artisan db:seed (optional - for test data)" -ForegroundColor White
Write-Host "5. Start backend: php artisan serve" -ForegroundColor White
Write-Host "6. Start frontend (new terminal): npm run dev" -ForegroundColor White
Write-Host "7. Open browser: http://127.0.0.1:8000" -ForegroundColor White
Write-Host ""
