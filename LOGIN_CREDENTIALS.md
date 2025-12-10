# 🔐 Login Credentials

## Default Test Accounts

After running `php artisan db:seed`, the following users are created:

### Admin User (Primary)
- **Email**: `admin@example.com`
- **Password**: `password`
- **Name**: Admin User
- **Purpose**: Primary administrator account

### Test User (Secondary)
- **Email**: `test@example.com`
- **Password**: `password`
- **Name**: Test User
- **Purpose**: Secondary test account

---

## 🚀 Quick Start

1. **Run migrations and seeder:**
   ```bash
   php artisan migrate:fresh --seed
   ```

2. **Start the server:**
   ```bash
   php artisan serve
   ```

3. **Access the application:**
   ```
   http://localhost:8000
   ```

4. **Login:**
   - Go to http://localhost:8000/login
   - Use either of the accounts above
   - Password for both: `password`

---

## 📊 Sample Data Created

The seeder creates:
- ✅ **2 test users** (admin@example.com, test@example.com)
- ✅ **20 active contracts**
- ✅ **8 contracts expiring soon** (within 30 days)
- ✅ **5 expired contracts**
- ✅ **Total: 33 contracts**

---

## 🔄 Reset Database (Fresh Start)

To reset everything and reseed:

```bash
php artisan migrate:fresh --seed
```

This will:
1. Drop all tables
2. Run all migrations
3. Seed with fresh data

---

## 🛠️ Create Additional Users

### Via Tinker
```bash
php artisan tinker
```

Then:
```php
User::create([
    'name' => 'Your Name',
    'email' => 'your@email.com',
    'password' => bcrypt('your_password')
]);
```

### Via Registration
- Visit: http://localhost:8000/register
- Fill in the registration form
- Create your own account

---

## 🔒 Password Security

⚠️ **Important for Production:**
- Change default passwords immediately
- Never use 'password' as a password in production
- Use strong, unique passwords
- Enable two-factor authentication if available

---

## 📝 Seeder Location

The login seeder code is in:
- **File**: `database/seeders/DatabaseSeeder.php`
- **Lines**: Creates 2 users with bcrypt hashed passwords

---

## ✅ Verification

After seeding, verify users were created:

```bash
php artisan tinker
```

Then:
```php
User::all(); // Shows all users
User::count(); // Should return 2
```

Or check directly in your database:
```sql
SELECT * FROM users;
```

---

## 🎯 What You Can Do After Login

1. **View Dashboard** - See contract statistics
2. **Manage Contracts** - Create, edit, delete contracts
3. **View Expiring Contracts** - See contracts expiring soon
4. **Update Profile** - Change your user information
5. **Logout** - Safely end your session

---

## 🐛 Troubleshooting

### Cannot login / "These credentials do not match our records"

**Solution 1**: Reseed the database
```bash
php artisan migrate:fresh --seed
```

**Solution 2**: Clear config cache
```bash
php artisan config:clear
php artisan cache:clear
```

**Solution 3**: Check database
```bash
php artisan tinker
User::where('email', 'admin@example.com')->first();
```

### Forgot password

Since this is development, just reseed:
```bash
php artisan db:seed --class=DatabaseSeeder
```

Or reset via tinker:
```bash
php artisan tinker
$user = User::where('email', 'admin@example.com')->first();
$user->password = bcrypt('newpassword');
$user->save();
```

---

## 📚 Related Documentation

- [QUICK_SETUP.md](QUICK_SETUP.md) - Quick setup guide
- [CONTRACT_MANAGEMENT_README.md](CONTRACT_MANAGEMENT_README.md) - Full documentation
- [SETUP_CHECKLIST.md](SETUP_CHECKLIST.md) - Setup verification

---

**Created**: December 10, 2025
**Status**: ✅ Working
**Default Password**: `password` (change in production!)
