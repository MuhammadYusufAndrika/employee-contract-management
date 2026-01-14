# Test User Credentials

After running `php artisan migrate:fresh --seed`, the following test users are available:

## 1. Super Admin

-   **Email**: superadmin@example.com
-   **Password**: password
-   **Role**: Super Admin
-   **Access**:
    -   Full system access
    -   Can manage users (create, edit, delete users)
    -   Can manage all data (employees, contracts, documents, layoffs)

## 2. Admin

-   **Email**: admin@example.com
-   **Password**: password
-   **Role**: Admin
-   **Access**:
    -   Full access to all features EXCEPT user management
    -   Can create, edit, delete: employees, contracts, documents, layoffs
    -   Cannot access User Management menu

## 3. Viewer

-   **Email**: viewer@example.com
-   **Password**: password
-   **Role**: Viewer
-   **Access**:
    -   View-only access to all features
    -   Cannot create, edit, or delete any data
    -   All action buttons (Create, Edit, Delete) are hidden

## Role Permission Summary

| Feature                      | Super Admin | Admin | Viewer |
| ---------------------------- | ----------- | ----- | ------ |
| User Management              | ✅          | ❌    | ❌     |
| View Employees               | ✅          | ✅    | ✅     |
| Create/Edit/Delete Employees | ✅          | ✅    | ❌     |
| View Contracts               | ✅          | ✅    | ✅     |
| Create/Edit/Delete Contracts | ✅          | ✅    | ❌     |
| View Documents               | ✅          | ✅    | ✅     |
| Create/Edit/Delete Documents | ✅          | ✅    | ❌     |
| View Layoffs                 | ✅          | ✅    | ✅     |
| Process/Edit/Delete Layoffs  | ✅          | ✅    | ❌     |
| View Dashboard               | ✅          | ✅    | ✅     |

## Implementation Details

### Permission Methods in User Model

-   `isSuperAdmin()`: Returns true only for super_admin role
-   `isAdmin()`: Returns true for super_admin OR admin (both have admin privileges)
-   `isViewer()`: Returns true only for viewer role
-   `canManageUsers()`: Returns true only for super_admin
-   `canModify()`: Returns true for super_admin or admin (can create/edit/delete)

### Views Updated

-   User Management menu: Only visible to super_admin (uses `canManageUsers()`)
-   Create/Edit/Delete buttons: Hidden from viewers (uses `canModify()`)
-   Role badges displayed in navigation with distinct colors:
    -   Super Admin: Red badge with star icon
    -   Admin: Yellow/warning badge with shield icon
    -   Viewer: Blue/info badge with eye icon
