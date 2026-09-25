# Banquet Management System - User Rights & Permissions

## Overview
The Banquet Management System uses a comprehensive role-based access control (RBAC) system powered by Spatie Laravel Permission. Each user is assigned a role with specific permissions to control access to different modules and features.

## User Roles

### 1. Super Admin
**Platform Owner** - Full system access
- Has access to all permissions across the entire platform
- Can manage tenants, subscriptions, system settings
- Can view and modify any tenant's data
- Full control over platform configuration

### 2. Tenant Owner
**Business Owner** - Full access to their banquet business
- Has complete access to all modules within their tenant
- Can manage business settings, staff, customers, bookings
- Can create and manage custom roles
- Full financial control and reporting access

### 3. Branch/General Manager
**Operational Manager** - Day-to-day operations management
- Can manage venues, services, bookings, quotations
- Can create and edit customer profiles
- Can manage invoices and receive payments
- Can create expenses and manage vendors
- Limited staff management (view, create, update)
- Can view reports and send notifications
- Cannot delete critical data or manage roles

### 4. Sales Officer
**Sales Staff** - Customer acquisition and booking management
- Can view dashboard and booking statistics
- Can create and manage bookings
- Can create and manage quotations
- Can add and update customers
- Can view and print invoices
- Can receive and record payments
- Can send WhatsApp notifications
- Cannot access financial reports or manage expenses

### 5. Accountant/Cashier
**Financial Staff** - Accounting and payment management
- Can view dashboard and financial statistics
- Can manage invoices (create, update, print, apply discounts)
- Can manage payments (create, update, print, process refunds)
- Can manage expenses and expense categories
- Can view customer and supplier ledgers
- Can view accounts summary
- Can view financial reports (revenue, expenses, outstanding, profit/loss)
- Can export reports
- Cannot manage bookings or quotations

### 6. Receptionist
**Front Desk Staff** - Customer service and basic operations
- Can view dashboard and booking statistics
- Can view venues
- Can view bookings and calendar
- Can add and update customers
- Can view and print quotations
- Cannot create bookings or manage finances

### 7. Vendor Manager
**Supplier Management** - Vendor and expense management
- Can view dashboard
- Can view bookings and calendar
- Can view and manage packages
- Can view and create expenses
- Can view supplier/vendor ledger
- Limited access focused on vendor relationships

## Module Permissions Breakdown

### 1. Dashboard
- `dashboard.view` - View main dashboard
- `dashboard.financial_stats.view` - View financial statistics
- `dashboard.booking_stats.view` - View booking statistics

### 2. Venues / Halls
- `venues.view` - View venue list
- `venues.create` - Add new venue
- `venues.update` - Edit venue details
- `venues.delete` - Delete venue

### 3. Services & Facilities
- `services.view` - View services list
- `services.create` - Add new service
- `services.update` - Edit service
- `services.delete` - Delete service

### 4. Packages & Menus
- `packages.view` - View packages and menus
- `packages.manage` - Manage packages and dishes

### 5. Bookings & Calendar
- `bookings.view` - View booking list
- `bookings.create` - Create new booking
- `bookings.update` - Edit booking
- `bookings.delete` - Delete booking
- `bookings.print` - Print booking/contract
- `bookings.status.change` - Change booking status (confirm/cancel)
- `bookings.calendar.view` - View booking calendar
- `bookings.block_dates` - Block/unblock dates

### 6. Quotations
- `quotations.view` - View quotations
- `quotations.create` - Create quotation
- `quotations.update` - Edit quotation
- `quotations.delete` - Delete quotation
- `quotations.print` - Print quotation
- `quotations.convert_to_booking` - Convert quotation to booking

### 7. Customers
- `customers.view` - View customer list
- `customers.create` - Add customer
- `customers.update` - Edit customer profile
- `customers.delete` - Delete customer
- `customers.export` - Export customer data

### 8. Invoices & Billing
- `invoices.view` - View invoices
- `invoices.create` - Generate invoice
- `invoices.update` - Edit invoice
- `invoices.delete` - Delete invoice
- `invoices.print` - Print/download invoice
- `invoices.discount.apply` - Apply special discount

### 9. Payments
- `payments.view` - View payment records
- `payments.create` - Receive/record payment
- `payments.update` - Edit payment record
- `payments.delete` - Delete payment
- `payments.print` - Print payment receipt
- `payments.refund` - Process payment refund

### 10. Expenses
- `expenses.view` - View business expenses
- `expenses.create` - Add expense entry
- `expenses.update` - Edit expense
- `expenses.delete` - Delete expense
- `expense_categories.manage` - Manage expense categories

### 11. Finance
- `finance.view` - View finance records
- `finance.create` - Create finance transactions

### 12. Vendors
- `vendors.view` - View vendors
- `vendors.create` - Add vendor
- `vendors.update` - Edit vendor
- `vendors.delete` - Delete vendor

### 13. Accounts & Ledger
- `ledgers.customer.view` - View customer ledger
- `ledgers.supplier.view` - View supplier/vendor ledger
- `accounts.summary.view` - View accounts & balance summary

### 14. Staff Management
- `staff.view` - View staff members
- `staff.create` - Add staff member
- `staff.update` - Edit staff & assign roles
- `staff.delete` - Delete staff account

### 15. Roles & Permissions
- `roles.view` - View roles
- `roles.create` - Create custom role
- `roles.edit` - Edit role & permissions
- `roles.delete` - Delete role
- `roles.manage` - Manage roles & permissions

### 16. Reports & Analytics
- `reports.bookings.view` - View bookings report
- `reports.revenue.view` - View revenue report
- `reports.expenses.view` - View expenses report
- `reports.outstanding_dues.view` - View outstanding balances
- `reports.profit_loss.view` - View profit & loss report
- `reports.export` - Export reports (Excel/PDF)

### 17. Notifications
- `notifications.send_whatsapp` - Send WhatsApp alerts
- `notifications.send_sms` - Send SMS notifications

### 18. Tenant Settings
- `tenant_settings.view` - View business settings
- `tenant_settings.update` - Update business settings & taxes

### 19. Subscription & Billing (Super Admin Only)
- `subscriptions.view` - View subscriptions
- `subscriptions.create` - Create subscription plan
- `subscriptions.update` - Edit subscription
- `subscriptions.delete` - Delete subscription
- `subscriptions.manage` - Manage subscriptions & billing

### 20. System Settings (Super Admin Only)
- `system_settings.view` - View system settings
- `system_settings.update` - Update system configuration
- `system.currencies.manage` - Manage currencies
- `system.tax_config.manage` - Configure tax settings

## Permission Matrix

| Module | Super Admin | Tenant Owner | Manager | Sales | Accountant | Receptionist | Vendor Manager |
|--------|-------------|--------------|---------|-------|------------|--------------|----------------|
| Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Venues | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| Services | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Packages | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ✅ |
| Bookings | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Quotations | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ |
| Customers | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Invoices | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ |
| Payments | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ |
| Expenses | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ | ✅ |
| Finance | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ | ✅ |
| Vendors | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ | ✅ |
| Ledgers | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ✅ |
| Staff | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Roles | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Reports | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ |
| Notifications | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| Settings | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Subscriptions | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| System Settings | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |

## Implementation Notes

### Tenant-Specific Roles
- Template roles are defined in `database/seeders/RolePermissionSeeder.php`
- When a new tenant is created, these template roles are copied to the tenant's scope
- Each tenant can customize roles and permissions independently
- Permission cache is cleared after role updates

### Permission Storage
- Permissions are stored in `permissions` table using Spatie Laravel Permission
- Roles are stored in `roles` table with tenant_id for multi-tenancy
- Role-Permission relationships in `role_has_permissions` table
- User-Role relationships in `model_has_roles` table

### Permission Checks in Code
```php
// Using @can directive in Blade
@can('bookings.create')
    <button>Create Booking</button>
@endcan

// Using user role helper methods
@if(auth()->user()->isManager())
    <button>Manager Action</button>
@endif

// Using permission check in controllers
$this->authorize('bookings.create', Booking::class);
```

### Security Considerations
- All database queries include tenant_id filtering
- API endpoints use middleware to check permissions
- Navigation menu items are conditionally displayed based on permissions
- Form actions are protected with authorization checks

## Usage Instructions

### Assigning Roles to Users
1. Navigate to Staff Management
2. Create or edit a staff member
3. Select the appropriate role from the dropdown
4. Save the changes

### Creating Custom Roles
1. Navigate to Roles & Permissions
2. Click "Create Custom Role"
3. Select permissions from the module categories
4. Save the custom role
5. Assign the custom role to staff members

### Managing Permissions
- Permissions are grouped by module for easy management
- Each permission has a clear, descriptive label
- Icons help identify module categories visually
- Changes to permissions take effect immediately after cache clearing

## Testing Permissions

### Manual Testing
1. Create test users with different roles
2. Log in as each role
3. Verify navigation menu shows correct options
4. Test module access and form submissions
5. Verify data isolation between tenants

### Automated Testing
```php
// Example test
public function test_sales_officer_cannot_delete_bookings()
{
    $user = User::factory()->create(['role' => 'sales_officer']);
    $this->actingAs($user)
        ->delete(route('bookings.destroy', $booking))
        ->assertStatus(403);
}
```

## Maintenance

### Adding New Permissions
1. Add permission enum to `app/Enums/PermissionEnum.php`
2. Update `module()` method for categorization
3. Update `label()` method for display text
4. Update `moduleIcon()` method for icon
5. Run seeder to create the permission
6. Assign to appropriate roles in `database/seeders/RolePermissionSeeder.php`

### Updating Role Permissions
1. Edit `database/seeders/RolePermissionSeeder.php`
2. Add/remove permissions from role arrays
3. Run seeder: `php artisan db:seed --class=RolePermissionSeeder`
4. Clear permission cache

### Best Practices
- Follow principle of least privilege
- Regularly review and audit permissions
- Use descriptive permission names
- Group related permissions by module
- Document custom roles and their intended use
- Test permission changes thoroughly before deployment
