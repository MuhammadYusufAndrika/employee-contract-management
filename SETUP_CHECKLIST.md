# 📋 Setup Checklist - Employee Contract Management System

## Pre-Setup Requirements

-   [ ] PHP 8.1+ installed
-   [ ] Composer installed
-   [ ] MySQL/PostgreSQL/SQLite database available
-   [ ] Laravel project dependencies installed (`composer install`)

---

## 🔧 Initial Setup Steps

### 1. Environment Configuration

-   [ ] Copy `.env.example` to `.env`
-   [ ] Configure database settings in `.env`:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=contract_management
    DB_USERNAME=root
    DB_PASSWORD=your_password
    ```
-   [ ] Generate application key: `php artisan key:generate`

### 2. Database Setup

-   [ ] Create database (if not exists): `CREATE DATABASE contract_management;`
-   [ ] Run migrations: `php artisan migrate`
-   [ ] Seed sample data (optional): `php artisan db:seed`

### 3. Test the Application

-   [ ] Start dev server: `php artisan serve`
-   [ ] Open browser: http://localhost:8000
-   [ ] Test login with:
    -   Email: test@example.com
    -   Password: password

---

## ✅ Feature Testing Checklist

### Navigation & UI

-   [ ] Navbar displays correctly
-   [ ] "Expiring Soon" badge shows count
-   [ ] All menu links work
-   [ ] Bootstrap styling loads properly
-   [ ] Icons display correctly

### Dashboard

-   [ ] Dashboard loads without errors
-   [ ] Statistics cards show correct counts
-   [ ] Recent contracts section displays
-   [ ] Expiring contracts section displays
-   [ ] Quick action buttons work

### Contract Management

-   [ ] View all contracts (`/contracts`)
-   [ ] Pagination works
-   [ ] Create new contract (`/contracts/create`)
    -   [ ] Form validation works
    -   [ ] Success message displays
    -   [ ] Redirects to list after creation
-   [ ] Edit contract
    -   [ ] Form pre-fills with data
    -   [ ] Validation works on update
    -   [ ] Success message displays
-   [ ] Delete contract
    -   [ ] Confirmation dialog appears
    -   [ ] Contract deleted successfully
    -   [ ] Success message displays

### Expiring Contracts Feature

-   [ ] Expiring contracts page loads (`/contracts-expiring`)
-   [ ] Only shows contracts expiring within 30 days
-   [ ] Color coding works (yellow/red)
-   [ ] Days remaining calculated correctly
-   [ ] Badge count matches list count

### Validation Testing

-   [ ] Try submitting empty form - shows errors
-   [ ] Try end_date before start_date - shows error
-   [ ] Try invalid date formats - shows error
-   [ ] All validation messages display properly

### Scheduler & Commands

-   [ ] Run manual check: `php artisan contracts:check-expiring`
-   [ ] Check output displays correctly
-   [ ] Check logs: `storage/logs/laravel.log`
-   [ ] Verify expiring contracts logged

---

## 🚀 Production Deployment Checklist

### Before Deployment

-   [ ] Set `APP_ENV=production` in `.env`
-   [ ] Set `APP_DEBUG=false` in `.env`
-   [ ] Generate new `APP_KEY` for production
-   [ ] Configure production database
-   [ ] Run `composer install --optimize-autoloader --no-dev`
-   [ ] Run `php artisan config:cache`
-   [ ] Run `php artisan route:cache`
-   [ ] Run `php artisan view:cache`

### Setup Scheduler (Choose One)

#### Windows - Task Scheduler

-   [ ] Open Task Scheduler
-   [ ] Create new task
-   [ ] Set trigger: Daily
-   [ ] Set action: Start a program
    -   Program: `C:\path\to\php.exe`
    -   Arguments: `artisan schedule:run`
    -   Start in: `D:\2025\management-kontrak`

#### Linux/Mac - Crontab

-   [ ] Edit crontab: `crontab -e`
-   [ ] Add line:
    ```
    * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
    ```
-   [ ] Save and exit

### Security Checklist

-   [ ] All routes protected with auth middleware
-   [ ] CSRF protection enabled
-   [ ] SQL injection protection (using Eloquent)
-   [ ] XSS protection (Blade escaping)
-   [ ] Validation on all inputs
-   [ ] Secure password hashing

### Performance Optimization

-   [ ] Enable query caching if needed
-   [ ] Consider adding database indexes
-   [ ] Enable OPcache in PHP
-   [ ] Use production-optimized Composer autoloader

---

## 🧪 Testing Scenarios

### Test Scenario 1: Create Contract

1. [ ] Login as test user
2. [ ] Click "Add New Contract"
3. [ ] Fill in all fields
4. [ ] Submit form
5. [ ] Verify contract appears in list
6. [ ] Check database has new record

### Test Scenario 2: Expiring Contract Alert

1. [ ] Create contract with end_date in 15 days
2. [ ] Check badge count increases
3. [ ] Visit expiring contracts page
4. [ ] Verify contract appears in list
5. [ ] Check color coding

### Test Scenario 3: Edit & Delete

1. [ ] Edit an existing contract
2. [ ] Change department name
3. [ ] Save changes
4. [ ] Verify changes saved
5. [ ] Delete a contract
6. [ ] Confirm deletion
7. [ ] Verify removed from list

### Test Scenario 4: Scheduler

1. [ ] Run: `php artisan contracts:check-expiring`
2. [ ] Check console output
3. [ ] Check log file
4. [ ] Verify correct contracts listed

### Test Scenario 5: Validation

1. [ ] Try creating contract with empty fields
2. [ ] Try end_date before start_date
3. [ ] Verify validation messages
4. [ ] Fill form correctly
5. [ ] Verify successful submission

---

## 📊 Data Verification

After seeding, verify:

-   [ ] Users table has 1 test user
-   [ ] Contracts table has 33 records
-   [ ] At least 8 contracts expiring within 30 days
-   [ ] At least 5 expired contracts
-   [ ] Dashboard stats match actual counts

---

## 🐛 Troubleshooting Checklist

If something doesn't work:

### Database Issues

-   [ ] Check `.env` database credentials
-   [ ] Verify database exists
-   [ ] Check migrations ran: `php artisan migrate:status`
-   [ ] Check table exists: `SHOW TABLES;`

### Page Not Loading

-   [ ] Clear cache: `php artisan cache:clear`
-   [ ] Clear config: `php artisan config:clear`
-   [ ] Clear views: `php artisan view:clear`
-   [ ] Check Laravel log: `storage/logs/laravel.log`

### Scheduler Not Working

-   [ ] Test command manually first
-   [ ] Check cron/task scheduler setup
-   [ ] Verify file permissions (Linux)
-   [ ] Check scheduler log output

### UI Issues

-   [ ] Check browser console for errors
-   [ ] Verify Bootstrap CDN loads
-   [ ] Check for PHP errors in views
-   [ ] Clear browser cache

### Validation Not Working

-   [ ] Check FormRequest authorize() returns true
-   [ ] Verify route uses FormRequest
-   [ ] Check validation rules syntax
-   [ ] Review error display in blade

---

## 📝 Customization Tasks (Optional)

-   [ ] Change app name in `.env`: `APP_NAME="Your Company"`
-   [ ] Modify expiration warning days (default: 30)
-   [ ] Customize email notifications
-   [ ] Add more departments
-   [ ] Add more work locations
-   [ ] Customize Bootstrap theme/colors
-   [ ] Add company logo to navbar

---

## 📚 Documentation Review

-   [ ] Read `CONTRACT_MANAGEMENT_README.md`
-   [ ] Read `QUICK_SETUP.md`
-   [ ] Read `IMPLEMENTATION_SUMMARY.md`
-   [ ] Understand project structure
-   [ ] Review all routes: `php artisan route:list`

---

## ✅ Final Verification

### All Systems Go?

-   [ ] Application runs without errors
-   [ ] All CRUD operations work
-   [ ] Validation functions correctly
-   [ ] Scheduler command runs
-   [ ] UI looks professional
-   [ ] No PHP/JavaScript errors
-   [ ] Database properly configured
-   [ ] Sample data loaded

---

## 🎉 Launch Ready!

When all checkboxes are complete:

-   [ ] System is ready for production use
-   [ ] All features tested and working
-   [ ] Documentation reviewed
-   [ ] Users can be onboarded

---

## 📞 Support

If you encounter issues:

1. Check `storage/logs/laravel.log`
2. Review documentation files
3. Run `php artisan --version` to verify Laravel
4. Check `php -v` for PHP version
5. Verify all composer dependencies: `composer install`

---

**Last Updated**: December 10, 2025
**Version**: 1.0.0

---

## 🔗 Quick Links

-   Start Server: `php artisan serve`
-   Run Tests: `php artisan test`
-   Check Routes: `php artisan route:list`
-   Check Scheduler: `php artisan schedule:list`
-   Clear Cache: `php artisan optimize:clear`
