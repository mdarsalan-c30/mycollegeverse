# 🔧 7. HOSTINGER SETUP GUIDE

## 🟢 STEP 1: Buy & Setup
*   Purchase Hostinger Premium (shared hosting or VPS).
*   Add and point your domain (e.g., `mycollegeverse.com`).

## 🟢 STEP 2: Install Laravel
*   Run `composer create-project laravel/laravel mycollegeverse` locally or via SSH.
*   Upload files via **File Manager** (ZIP) or **Git** (Highly Recommended).

## 🟢 STEP 3: Configure `.env`
Update your database credentials:
```env
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_pass
```

## 🟢 STEP 4: DB Migration
Run via SSH or a cron job if SSH is limited:
`php artisan migrate`

## 🟢 STEP 5: Storage Link
Enable public access to uploads:
`php artisan storage:link`

## 🟢 STEP 6: File Upload Setup
*   Store files in `/storage/app/public/notes`.
*   Access via URL: `/storage/notes/...`
