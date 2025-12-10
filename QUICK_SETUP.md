# Quick Setup Guide - Employee Contract Management System

## 🚀 Quick Start (5 Minutes)

### Step 1: Database Configuration

Edit `.env` file:

```env
DB_DATABASE=contract_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 2: Setup Database

```bash
php artisan migrate
php artisan db:seed
```

### Step 3: Run Application

```bash
php artisan serve
```

### Step 4: Login

-   URL: http://localhost:8000/login
-   Email: test@example.com
-   Password: password

## 📋 Available Commands

### Run Migrations

```bash
php artisan migrate
```

### Seed Sample Data

```bash
php artisan db:seed
```

Creates:

-   1 test user
-   20 active contracts
-   8 contracts expiring soon
-   5 expired contracts

### Check Expiring Contracts (Manual)

```bash
php artisan contracts:check-expiring
```

### Run Scheduler (Development)

```bash
php artisan schedule:work
```

### Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📁 Main Routes

| Route           | URL                   | Description                        |
| --------------- | --------------------- | ---------------------------------- |
| Dashboard       | `/dashboard`          | Main dashboard with statistics     |
| All Contracts   | `/contracts`          | List all contracts                 |
| Create Contract | `/contracts/create`   | Add new contract                   |
| Expiring Soon   | `/contracts-expiring` | View contracts expiring in 30 days |

## 🔧 Configuration

### Change Expiration Warning Days

File: `app/Models/Contract.php`

```php
// Change from 30 days to desired number
public static function expiringWithinDays(int $days = 30)
```

### Scheduler Frequency

File: `routes/console.php`

```php
// Change from daily to desired frequency
Schedule::command('contracts:check-expiring')->daily();
```

Options:

-   `->everyMinute()`
-   `->hourly()`
-   `->daily()`
-   `->weekly()`
-   `->monthly()`

## 🎨 UI Customization

### Bootstrap Theme

Bootstrap 5 is loaded via CDN in:
`resources/views/layouts/bootstrap.blade.php`

To use a different theme:

1. Replace Bootstrap CDN link
2. Or add custom CSS in the same file

### Colors & Badges

Modify in blade templates:

-   `bg-danger` - Red (expired/urgent)
-   `bg-warning` - Yellow (expiring soon)
-   `bg-success` - Green (active)
-   `bg-primary` - Blue (info)

## 🐛 Troubleshooting

### Error: "Base table or view not found"

**Solution**: Run `php artisan migrate`

### Error: "Class 'Contract' not found"

**Solution**: Run `composer dump-autoload`

### Scheduler Not Running

**Solution**:

-   Development: Use `php artisan schedule:work`
-   Production: Setup cron job (see main README)

### Badge Not Showing

**Solution**:

```bash
php artisan cache:clear
php artisan config:clear
```

## 📊 Sample Test Data

After seeding, you'll have:

-   **Total Contracts**: 33
-   **Active**: 20
-   **Expiring Soon**: 8
-   **Expired**: 5

## 🔒 Default Test User

-   **Email**: test@example.com
-   **Password**: password

## 📝 Contract Form Fields

| Field         | Type | Required | Validation               |
| ------------- | ---- | -------- | ------------------------ |
| Employee Name | Text | Yes      | Max 255 chars            |
| Start Date    | Date | Yes      | Valid date               |
| End Date      | Date | Yes      | Must be after start date |
| Department    | Text | Yes      | Max 255 chars            |
| Work Location | Text | Yes      | Max 255 chars            |

## 🎯 Next Steps

1. ✅ Login with test credentials
2. ✅ Explore the dashboard
3. ✅ Create a new contract
4. ✅ View expiring contracts
5. ✅ Test edit/delete features
6. ✅ Run scheduler command manually
7. ✅ Check logs for scheduled tasks

## 📚 Documentation

For detailed documentation, see `CONTRACT_MANAGEMENT_README.md`

---

**Need Help?** Check the main README or logs at `storage/logs/laravel.log`
