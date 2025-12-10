# 🏗️ System Architecture & Flow

## System Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                   Employee Contract Management System            │
│                        Laravel 11 Application                    │
└─────────────────────────────────────────────────────────────────┘
                                  │
                    ┌─────────────┴─────────────┐
                    │                           │
            ┌───────▼────────┐         ┌───────▼────────┐
            │   Web Routes   │         │    Scheduler    │
            │   (Auth)       │         │  (Daily Task)   │
            └───────┬────────┘         └───────┬────────┘
                    │                           │
        ┌───────────┼───────────┐              │
        │           │           │              │
   ┌────▼────┐ ┌───▼────┐ ┌───▼─────┐   ┌────▼────┐
   │Dashboard│ │Contract│ │Expiring │   │ Command │
   │         │ │  CRUD  │ │ Alert   │   │  Check  │
   └────┬────┘ └───┬────┘ └───┬─────┘   └────┬────┘
        │          │           │              │
        └──────────┴───────────┴──────────────┘
                       │
                  ┌────▼─────┐
                  │ Contract │
                  │  Model   │
                  └────┬─────┘
                       │
                  ┌────▼─────┐
                  │Database  │
                  │contracts │
                  │  table   │
                  └──────────┘
```

## Request Flow Diagram

### 1. View Contracts List

```
User → /contracts → ContractController@index → Contract Model
                                              ↓
                                        Fetch all contracts
                                              ↓
                                        Return to view
                                              ↓
                                  contracts/index.blade.php
                                              ↓
                                      Display table
```

### 2. Create New Contract

```
User → /contracts/create → ContractController@create
                                    ↓
                          Show create form
                                    ↓
                        contracts/create.blade.php
                                    ↓
                          User fills form
                                    ↓
                POST /contracts → StoreContractRequest
                                    ↓
                              Validate input
                                    ↓
                        ContractController@store
                                    ↓
                           Contract::create()
                                    ↓
                           Save to database
                                    ↓
                      Redirect with success message
```

### 3. Expiring Contracts Check

```
Scheduler (Daily) → contracts:check-expiring command
                                ↓
                    Contract::expiringWithinDays(30)
                                ↓
                        Query contracts table
                                ↓
                    WHERE end_date BETWEEN now() AND +30 days
                                ↓
                        Return collection
                                ↓
                      Log warnings to file
                                ↓
                      Display in console
```

## Data Flow Architecture

```
┌──────────────┐
│  Bootstrap   │ ← User Interface Layer
│   Views      │
└──────┬───────┘
       │
┌──────▼───────┐
│  Controllers │ ← Request Handling Layer
└──────┬───────┘
       │
┌──────▼───────┐
│   Models     │ ← Business Logic Layer
└──────┬───────┘
       │
┌──────▼───────┐
│  Database    │ ← Data Persistence Layer
│  (MySQL)     │
└──────────────┘
```

## Component Interactions

### Dashboard Components

```
Dashboard View
    │
    ├── Statistics Cards
    │   ├── Total Contracts (Contract::count())
    │   ├── Active Contracts (where end_date >= now)
    │   ├── Expiring Soon (expiringWithinDays(30))
    │   └── Expired (where end_date < now)
    │
    ├── Expiring Contracts Widget
    │   └── Contract::expiringWithinDays(30)->take(5)
    │
    └── Recent Contracts Widget
        └── Contract::orderBy('created_at')->take(5)
```

### Navigation Badge System

```
AppServiceProvider (boot)
    │
    └── View Composer (all views)
        │
        └── IF user authenticated
            │
            └── Contract::expiringWithinDays(30)->count()
                │
                └── Share as $globalExpiringCount
                    │
                    └── Display in navbar badge
```

## File Structure Map

```
Contract Management System
│
├── 📁 app/
│   ├── 📁 Console/Commands/
│   │   └── 📄 CheckExpiringContracts.php [Scheduler Command]
│   │
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/
│   │   │   └── 📄 ContractController.php [CRUD Logic]
│   │   │
│   │   └── 📁 Requests/
│   │       ├── 📄 StoreContractRequest.php [Create Validation]
│   │       └── 📄 UpdateContractRequest.php [Update Validation]
│   │
│   ├── 📁 Models/
│   │   └── 📄 Contract.php [Business Logic & DB Model]
│   │
│   └── 📁 Providers/
│       └── 📄 AppServiceProvider.php [View Composer]
│
├── 📁 database/
│   ├── 📁 factories/
│   │   └── 📄 ContractFactory.php [Test Data Generator]
│   │
│   ├── 📁 migrations/
│   │   └── 📄 *_create_contracts_table.php [Schema]
│   │
│   └── 📁 seeders/
│       └── 📄 DatabaseSeeder.php [Sample Data]
│
├── 📁 resources/views/
│   ├── 📁 contracts/
│   │   ├── 📄 index.blade.php [List View]
│   │   ├── 📄 create.blade.php [Create Form]
│   │   ├── 📄 edit.blade.php [Edit Form]
│   │   └── 📄 expiring.blade.php [Expiring List]
│   │
│   ├── 📁 layouts/
│   │   ├── 📄 bootstrap.blade.php [Main Layout]
│   │   └── 📄 bootstrap-nav.blade.php [Navigation]
│   │
│   └── 📄 dashboard.blade.php [Dashboard View]
│
└── 📁 routes/
    ├── 📄 web.php [HTTP Routes]
    └── 📄 console.php [Scheduler Config]
```

## Database Schema

```sql
┌─────────────────────────────────┐
│     contracts table             │
├─────────────────────────────────┤
│ id              BIGINT PK       │
│ employee_name   VARCHAR(255)    │
│ start_date      DATE            │
│ end_date        DATE            │
│ department      VARCHAR(255)    │
│ work_location   VARCHAR(255)    │
│ created_at      TIMESTAMP       │
│ updated_at      TIMESTAMP       │
└─────────────────────────────────┘

Indexes:
- PRIMARY KEY (id)
- INDEX (end_date) [For expiry queries]
```

## Route Structure

```
/
├── GET  /dashboard                    → Dashboard view
│
├── GET  /contracts                    → List all contracts
├── GET  /contracts/create             → Show create form
├── POST /contracts                    → Store new contract
├── GET  /contracts/{id}/edit          → Show edit form
├── PUT  /contracts/{id}               → Update contract
├── DELETE /contracts/{id}             → Delete contract
│
└── GET  /contracts-expiring           → List expiring contracts
```

## Middleware Stack

```
Request
  │
  ├─→ CSRF Protection
  │     │
  ├─→ Authentication (auth)
  │     │
  ├─→ Verified Email (verified)
  │     │
  └─→ Controller Action
        │
        └─→ Response
```

## Validation Flow

```
Form Submission
      │
      ▼
StoreContractRequest / UpdateContractRequest
      │
      ├─→ authorize() → Check if user allowed
      │
      └─→ rules() → Validate fields
            │
            ├─→ Valid ──→ Continue to Controller
            │
            └─→ Invalid ──→ Redirect back with errors
```

## Scheduler Execution Flow

```
Laravel Scheduler (Cron)
      │
      ▼
routes/console.php
      │
      └─→ Schedule::command('contracts:check-expiring')->daily()
            │
            ▼
      CheckExpiringContracts Command
            │
            ├─→ Contract::expiringWithinDays(30)
            │
            ├─→ Foreach contract
            │     │
            │     ├─→ Calculate days remaining
            │     │
            │     └─→ Log warning message
            │
            └─→ Display summary in console
```

## State Diagram - Contract Lifecycle

```
┌──────────┐
│  Created │ (New contract added)
└────┬─────┘
     │
     ▼
┌──────────┐
│  Active  │ (Current date < end_date)
└────┬─────┘
     │
     ▼
┌──────────┐
│ Expiring │ (30 days or less until end_date)
│   Soon   │
└────┬─────┘
     │
     ▼
┌──────────┐
│ Expired  │ (Current date > end_date)
└──────────┘
```

## Color Coding System

```
Contract Status Colors:

🟢 Active (Green)
   - end_date > now + 30 days
   - Badge: bg-success

🟡 Expiring Soon (Yellow)
   - end_date between now and +30 days
   - Badge: bg-warning

🔴 Urgent (Red)
   - end_date within 7 days
   - Badge: bg-danger

⚫ Expired (Dark Red)
   - end_date < now
   - Badge: bg-danger
```

## Security Layers

```
┌─────────────────────────────────┐
│   CSRF Token Verification      │ ← All POST/PUT/DELETE requests
├─────────────────────────────────┤
│   Authentication Required       │ ← auth middleware
├─────────────────────────────────┤
│   Input Validation             │ ← FormRequest classes
├─────────────────────────────────┤
│   SQL Injection Protection     │ ← Eloquent ORM
├─────────────────────────────────┤
│   XSS Protection              │ ← Blade {{ }} escaping
└─────────────────────────────────┘
```

## Performance Considerations

```
Optimization Points:

1. Database Queries
   └─→ Use pagination on index (15 per page)
   └─→ Eager loading if relationships added
   └─→ Index on end_date for expiry queries

2. View Caching
   └─→ Production: php artisan view:cache
   └─→ Config caching: php artisan config:cache

3. Query Caching
   └─→ Cache expiring count in production
   └─→ Invalidate on create/update/delete

4. Asset Loading
   └─→ Bootstrap loaded via CDN
   └─→ Browser caching enabled
```

## Notification System Flow

```
User visits any page
      │
      ▼
AppServiceProvider View Composer
      │
      ├─→ Check if user authenticated
      │
      └─→ Yes → Contract::expiringWithinDays(30)->count()
            │
            └─→ Share as $globalExpiringCount
                  │
                  └─→ Available in all views
                        │
                        └─→ Navbar displays badge if > 0
```

---

## Key Integration Points

### 1. Controller ↔ Model

```php
// Controller uses Model methods
Contract::expiringWithinDays(30)
$contract->isExpiringSoon()
$contract->daysUntilExpiration()
```

### 2. View ↔ Controller

```php
// Controller passes data to view
return view('contracts.index', compact('contracts'));
```

### 3. Routes ↔ Controller

```php
// Resource routing
Route::resource('contracts', ContractController::class);
```

### 4. Scheduler ↔ Command

```php
// Scheduler calls command
Schedule::command('contracts:check-expiring')->daily();
```

---

**Visual Guide Version**: 1.0.0
**Created**: December 10, 2025
