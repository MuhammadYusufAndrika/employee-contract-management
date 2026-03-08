# 📋 Management Kontrak

A web-based **Employee Contract Management System** built with Laravel 12. Designed to help HR teams manage employee contracts, track expiry dates, handle layoffs, and maintain document libraries — all in one place.

---

## ✨ Features

### 👥 Employee Management
- Add, edit, view, and delete employee records
- Upload employee CV / document files
- Filter employees by status (Active, Permanent, Expiring Soon, Expired)
- Filter by department and work location
- View full contract history per employee
- Add a new contract directly from an employee's detail page

### 📄 Contract Management
- Create and manage employee contracts (Fixed-term `Kontrak` or Permanent `KPP`)
- Contract list only shows **latest contract per employee** (no duplicates from renewals)
- Filter contracts by status, department, work location, and keyword
- Upload contract PDF files
- View, edit, and delete contracts
- Contract renewal support

### ⚠️ Expiring Contracts
- Dedicated expiring contracts page with period filters (1 month, 3 months, 6 months, 1 year, 1 year+)
- Dashboard alerts for contracts expiring within 30 days
- Color-coded badges (red for ≤7 days, yellow for ≤30 days)

### 🚫 Layoff Management
- Process employee layoffs with reason and letter upload
- **Restore** layoff record — returns employee to the active list
- **Permanent delete** — removes employee and all associated data permanently
- Layoff records tracked with date, reason, letter PDF, and processed-by user

### 📊 Dashboard Analytics
- Contract statistics cards (Total, Active, Expiring Soon, Expired)
- Donut chart — contract status distribution with center total count
- Bar chart — contracts breakdown by department (top 6)
- Contracts expiring soon list (name, job position, work location)
- Recent contracts list (name, job position, work location)
- Quick action buttons

### 📚 Document Library
- Upload and manage legal/regulatory documents
- Fields: title, document number, type, theme, enacted date, published date
- Track download counts
- Active/inactive status toggle

### 🔐 User & Role Management
- Three roles: `super_admin`, `admin`, `viewer`
- **Super Admin / Admin** — full access (create, edit, delete)
- **Viewer** — read-only access
- Admin-only user management panel

### 📋 Contract History
- Full audit trail of contract changes per employee
- Search contract history by NIK

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Bootstrap 5.3, Bootstrap Icons |
| Charts | Chart.js 4.4 |
| Auth | Laravel Breeze |
| Database | MySQL |
| Build Tool | Vite |

---

## 🚀 Installation

### Requirements
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

### Steps

```bash
# 1. Clone the repository
git clone <repository-url>
cd management-kontrak

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# 5. Configure your database in .env
DB_DATABASE=management_kontrak
DB_USERNAME=root
DB_PASSWORD=

# 6. Run migrations and seed data
php artisan migrate --seed

# 7. Link storage for file uploads
php artisan storage:link

# 8. Build assets
npm run build

# 9. Start the development server
php artisan serve
```

Visit `http://localhost:8000`

---

## 👤 Default Accounts

| Role | Email | Password |
|---|---|---|
| Super Admin | superadmin@example.com | password |
| Admin | admin@example.com | password |
| Viewer | viewer@example.com | password |

---

## 📁 Project Structure

```
app/
├── Http/Controllers/
│   ├── ContractController.php       # Contract CRUD + expiring + renew
│   ├── ContractHistoryController.php
│   ├── DocumentController.php
│   ├── EmployeeController.php       # Employee CRUD + filters + expired
│   ├── LayoffController.php         # Layoff + restore + permanent delete
│   └── UserController.php
├── Models/
│   ├── Contract.php                 # latestPerEmployee(), expiringWithinDays()
│   ├── ContractHistory.php
│   ├── Document.php
│   ├── Employee.php
│   ├── Layoff.php
│   └── User.php
resources/views/
├── layouts/
│   ├── bootstrap.blade.php          # Main app layout with sidebar
│   └── bootstrap-nav.blade.php      # Sidebar navigation
├── dashboard.blade.php              # Dashboard with charts & analytics
├── contracts/                       # Contract views
├── employees/                       # Employee views
├── layoffs/                         # Layoff views
├── documents/                       # Document library views
└── users/                           # User management views
```

---

## 📌 Key Business Rules

- The **contract list** and **dashboard** always show the **latest contract per employee** (determined by most recent `start_date`)
- The **employee detail page** shows the **full contract history**
- Employees with a `KPP` contract type or no end date are considered **Permanent**
- **Expiring Soon** = contract expiring within 30 days
- **Active** employees = Permanent + Active (not expired, not layoff)
- Layoff employees are excluded from all active employee lists

---

## 📄 License

This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

