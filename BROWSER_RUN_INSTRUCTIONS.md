# How to Run the Project in Browser

Follow these steps to get the application running on your local machine using MySQL.

## Prerequisites
- **MySQL Server** must be running (e.g., via XAMPP, WAMP, Laragon, or standalone MySQL service).
- **Node.js** and **NPM** must be installed.
- **PHP** and **Composer** must be installed.

## Step 1: Database Setup
1. Open your MySQL client (phpMyAdmin, MySQL Workbench, or command line).
2. Create a new empty database named `unihub` (as specified in your `.env` file).
   ```sql
   CREATE DATABASE unihub;
   ```
3. Check your `.env` file to ensure the credentials match your local MySQL setup:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=unihub
   DB_USERNAME=root      # Change if your user is different
   DB_PASSWORD=          # Add password if you have one
   ```

## Step 2: Install Dependencies
Run the following commands in your project terminal:
```powershell
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

## Step 3: Setup Database Schema & Data
Run the migrations to create tables and seeders to populate initial data:
```powershell
# Run migrations
php artisan migrate

# (Optional) Seed the database with fake data
php artisan db:seed
```

## Step 4: Start the Application
You need to run **two** separate terminal commands to start the servers.

**Terminal 1 (Backend Server):**
```powershell
php artisan serve
```
*This will start the PHP server, usually at http://127.0.0.1:8000*

**Terminal 2 (Frontend Assets):**
```powershell
npm run dev
```
*This starts the Vite server for hot-reloading styles and scripts.*

## Step 5: Access in Browser
Open your web browser and go to:
**[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---
**Troubleshooting:**
- If you see "Connection refused", ensure your MySQL server is started.
- If you see "Unknown database 'unihub'", ensure you ran the `CREATE DATABASE` command.
