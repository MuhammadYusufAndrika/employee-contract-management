# Employee Contract Management System

A comprehensive Laravel-based system for managing employee contracts with expiration tracking, notifications, and automated daily checks.

## Features

✅ **Contract Management**

-   Create, read, update, and delete employee contracts
-   Store employee name, start date, end date, department, and work location
-   Form validation for all inputs

✅ **Dashboard**

-   Overview statistics (Total, Active, Expiring, Expired contracts)
-   Recent contracts list
-   Quick action buttons
-   Visual status indicators

✅ **Expiration Tracking**

-   Automatic detection of contracts expiring within 30 days
-   Color-coded warnings (yellow for expiring soon, red for urgent/expired)
-   Badge notification in navbar showing count of expiring contracts
-   Dedicated page for viewing all expiring contracts

✅ **Laravel Scheduler**

-   Daily automated check for expiring contracts
-   Logs warnings for contracts expiring soon
-   Command: `php artisan contracts:check-expiring`

✅ **Modern UI**

-   Bootstrap 5 design
-   Responsive layout
-   Bootstrap Icons
-   Clean and professional interface

## Requirements

-   PHP 8.1 or higher
-   Composer
-   MySQL/PostgreSQL/SQLite
-   Node.js & NPM (for frontend assets)

## Installation

### 1. Clone and Setup

```bash
cd d:\2025\management-kontrak
composer install
```

### 2. Environment Configuration

Copy `.env.example` to `.env` and configure your database:

```bash
copy .env.example .env
```

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=contract_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

Generate application key:

```bash
php artisan key:generate
```

### 3. Database Setup

Run migrations to create tables:

```bash
php artisan migrate
```

Seed database with sample data (optional):

```bash
php artisan db:seed
```

This will create:

-   1 test user (email: test@example.com, password: password)
-   20 active contracts
-   8 contracts expiring soon
-   5 expired contracts

### 4. Run the Application

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Usage

### Login Credentials

-   **Email**: test@example.com
-   **Password**: password

### Main Features

#### 1. Dashboard

Navigate to `/dashboard` after login to see:

-   Contract statistics
-   Expiring contracts alerts
-   Recent contracts
-   Quick action buttons

#### 2. Contract Management

-   **View All**: `/contracts` - List all contracts with pagination
-   **Create New**: `/contracts/create` - Add new employee contract
-   **Edit**: Click edit button on any contract
-   **Delete**: Click delete button with confirmation

#### 3. Expiring Contracts

-   **View Expiring**: `/contracts-expiring` - See all contracts expiring within 30 days
-   **Notification Badge**: Navbar shows count of expiring contracts
-   **Color Coding**:
    -   Yellow: Expiring within 30 days
    -   Red: Expiring within 7 days or expired

### Laravel Scheduler Setup

To enable daily automated checks, you need to setup Laravel's task scheduler:

#### On Windows:

1. Open Task Scheduler
2. Create a new task that runs daily
3. Action: Start a program
4. Program: `C:\path\to\php.exe`
5. Arguments: `artisan schedule:run`
6. Start in: `D:\2025\management-kontrak`

Or run manually:

```bash
php artisan schedule:work
```

#### On Linux/Mac:

Add to crontab:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Manual Check Command

Run the expiring contracts check manually:

```bash
php artisan contracts:check-expiring
```

## Project Structure

```
app/
├── Console/Commands/
│   └── CheckExpiringContracts.php    # Daily scheduler command
├── Http/
│   ├── Controllers/
│   │   └── ContractController.php     # CRUD operations
│   └── Requests/
│       ├── StoreContractRequest.php   # Create validation
│       └── UpdateContractRequest.php  # Update validation
└── Models/
    └── Contract.php                    # Contract model with helper methods

database/
├── factories/
│   └── ContractFactory.php            # Test data factory
├── migrations/
│   └── *_create_contracts_table.php   # Database schema
└── seeders/
    └── DatabaseSeeder.php             # Sample data seeder

resources/views/
├── contracts/
│   ├── index.blade.php                # List view
│   ├── create.blade.php               # Create form
│   ├── edit.blade.php                 # Edit form
│   └── expiring.blade.php             # Expiring contracts view
├── layouts/
│   ├── bootstrap.blade.php            # Bootstrap layout
│   └── bootstrap-nav.blade.php        # Navigation menu
└── dashboard.blade.php                # Dashboard view

routes/
├── web.php                            # Web routes
└── console.php                        # Scheduler configuration
```

## API Endpoints / Routes

### Web Routes (Protected by Auth)

| Method | URI                    | Name               | Description             |
| ------ | ---------------------- | ------------------ | ----------------------- |
| GET    | `/dashboard`           | dashboard          | Dashboard home          |
| GET    | `/contracts`           | contracts.index    | List all contracts      |
| GET    | `/contracts/create`    | contracts.create   | Show create form        |
| POST   | `/contracts`           | contracts.store    | Store new contract      |
| GET    | `/contracts/{id}/edit` | contracts.edit     | Show edit form          |
| PUT    | `/contracts/{id}`      | contracts.update   | Update contract         |
| DELETE | `/contracts/{id}`      | contracts.destroy  | Delete contract         |
| GET    | `/contracts-expiring`  | contracts.expiring | List expiring contracts |

## Validation Rules

### Contract Input Validation

```php
'employee_name' => 'required|string|max:255'
'start_date' => 'required|date'
'end_date' => 'required|date|after:start_date'
'department' => 'required|string|max:255'
'work_location' => 'required|string|max:255'
```

## Model Methods

### Contract Model

```php
// Get contracts expiring within specified days
Contract::expiringWithinDays(30)

// Check if contract is expiring soon
$contract->isExpiringSoon()

// Get days until expiration
$contract->daysUntilExpiration()
```

## Testing

Run the scheduler command manually to test:

```bash
php artisan contracts:check-expiring
```

Check logs:

```bash
tail -f storage/logs/laravel.log
```

## Customization

### Change Expiration Warning Period

Edit `app/Models/Contract.php`:

```php
public static function expiringWithinDays(int $days = 30)
```

### Modify Scheduler Frequency

Edit `routes/console.php`:

```php
Schedule::command('contracts:check-expiring')
    ->daily()           // Change to: ->hourly(), ->weekly(), etc.
```

### Customize Notification Badge

Edit `resources/views/layouts/bootstrap-nav.blade.php` to modify the notification badge appearance.

## Technologies Used

-   **Backend**: Laravel 11
-   **Frontend**: Bootstrap 5, Bootstrap Icons
-   **Database**: MySQL (configurable)
-   **Authentication**: Laravel Breeze
-   **Scheduler**: Laravel Task Scheduling
-   **Validation**: Laravel Form Requests

## Troubleshooting

### Issue: Scheduler not running

**Solution**: Make sure you've set up the cron job or Task Scheduler as described above.

### Issue: Badge not showing count

**Solution**: Clear cache with `php artisan cache:clear` and `php artisan config:clear`

### Issue: Validation errors on dates

**Solution**: Ensure dates are in `YYYY-MM-DD` format and end_date is after start_date.

## Future Enhancements

-   [ ] Email notifications for expiring contracts
-   [ ] Contract renewal workflow
-   [ ] Document attachment support
-   [ ] Export to PDF/Excel
-   [ ] Advanced search and filtering
-   [ ] Contract templates
-   [ ] Department-based permissions
-   [ ] Activity logs

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues and questions, please create an issue in the project repository.

---

**Last Updated**: December 10, 2025
**Version**: 1.0.0
