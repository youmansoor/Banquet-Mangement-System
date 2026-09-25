<?php

namespace App\Enums;

enum PermissionEnum: string
{
    // 1. Dashboard
    case DASHBOARD_VIEW = 'dashboard.view';
    case DASHBOARD_FINANCIAL_VIEW = 'dashboard.financial_stats.view';
    case DASHBOARD_BOOKINGS_VIEW = 'dashboard.booking_stats.view';

    // 2. Venues / Halls
    case VENUES_VIEW = 'venues.view';
    case VENUES_CREATE = 'venues.create';
    case VENUES_UPDATE = 'venues.update';
    case VENUES_DELETE = 'venues.delete';

    // 3. Services
    case SERVICES_VIEW = 'services.view';
    case SERVICES_CREATE = 'services.create';
    case SERVICES_UPDATE = 'services.update';
    case SERVICES_DELETE = 'services.delete';

    // 4. Packages & Menus
    case PACKAGES_VIEW = 'packages.view';
    case PACKAGES_MANAGE = 'packages.manage';

    // 5. Bookings & Calendar
    case BOOKINGS_VIEW = 'bookings.view';
    case BOOKINGS_CREATE = 'bookings.create';
    case BOOKINGS_UPDATE = 'bookings.update';
    case BOOKINGS_DELETE = 'bookings.delete';
    case BOOKINGS_PRINT = 'bookings.print';
    case BOOKINGS_STATUS_CHANGE = 'bookings.status.change';
    case BOOKINGS_CALENDAR_VIEW = 'bookings.calendar.view';
    case BOOKINGS_BLOCK_DATES = 'bookings.block_dates';

    // 6. Quotations
    case QUOTATIONS_VIEW = 'quotations.view';
    case QUOTATIONS_CREATE = 'quotations.create';
    case QUOTATIONS_UPDATE = 'quotations.update';
    case QUOTATIONS_DELETE = 'quotations.delete';
    case QUOTATIONS_PRINT = 'quotations.print';
    case QUOTATIONS_CONVERT = 'quotations.convert_to_booking';

    // 7. Customers
    case CUSTOMERS_VIEW = 'customers.view';
    case CUSTOMERS_CREATE = 'customers.create';
    case CUSTOMERS_UPDATE = 'customers.update';
    case CUSTOMERS_DELETE = 'customers.delete';
    case CUSTOMERS_EXPORT = 'customers.export';

    // 8. Invoices & Billing
    case INVOICES_VIEW = 'invoices.view';
    case INVOICES_CREATE = 'invoices.create';
    case INVOICES_UPDATE = 'invoices.update';
    case INVOICES_DELETE = 'invoices.delete';
    case INVOICES_PRINT = 'invoices.print';
    case INVOICES_DISCOUNT_APPLY = 'invoices.discount.apply';

    // 9. Payments
    case PAYMENTS_VIEW = 'payments.view';
    case PAYMENTS_CREATE = 'payments.create';
    case PAYMENTS_UPDATE = 'payments.update';
    case PAYMENTS_DELETE = 'payments.delete';
    case PAYMENTS_PRINT = 'payments.print';
    case PAYMENTS_REFUND = 'payments.refund';

    // 10. Expenses
    case EXPENSES_VIEW = 'expenses.view';
    case EXPENSES_CREATE = 'expenses.create';
    case EXPENSES_UPDATE = 'expenses.update';
    case EXPENSES_DELETE = 'expenses.delete';
    case EXPENSES_CATEGORIES = 'expense_categories.manage';

    // 10.5 Finance
    case FINANCE_VIEW = 'finance.view';
    case FINANCE_CREATE = 'finance.create';

    // 10.6 Vendors
    case VENDORS_VIEW = 'vendors.view';
    case VENDORS_CREATE = 'vendors.create';
    case VENDORS_UPDATE = 'vendors.update';
    case VENDORS_DELETE = 'vendors.delete';

    // 11. Accounts & Ledger
    case LEDGERS_CUSTOMER_VIEW = 'ledgers.customer.view';
    case LEDGERS_SUPPLIER_VIEW = 'ledgers.supplier.view';
    case ACCOUNTS_SUMMARY_VIEW = 'accounts.summary.view';

    // 12. Staff Management
    case STAFF_VIEW = 'staff.view';
    case STAFF_CREATE = 'staff.create';
    case STAFF_UPDATE = 'staff.update';
    case STAFF_DELETE = 'staff.delete';

    // 13. Roles & Permissions
    case ROLES_VIEW = 'roles.view';
    case ROLES_CREATE = 'roles.create';
    case ROLES_EDIT = 'roles.edit';
    case ROLES_DELETE = 'roles.delete';
    case ROLES_MANAGE = 'roles.manage';

    // 14. Reports
    case REPORTS_BOOKINGS_VIEW = 'reports.bookings.view';
    case REPORTS_REVENUE_VIEW = 'reports.revenue.view';
    case REPORTS_EXPENSES_VIEW = 'reports.expenses.view';
    case REPORTS_OUTSTANDING_VIEW = 'reports.outstanding_dues.view';
    case REPORTS_PROFIT_LOSS_VIEW = 'reports.profit_loss.view';
    case REPORTS_EXPORT = 'reports.export';

    // 15. Notifications
    case NOTIFICATIONS_WHATSAPP = 'notifications.send_whatsapp';
    case NOTIFICATIONS_SMS = 'notifications.send_sms';

    // 16. Settings
    case TENANT_SETTINGS_VIEW = 'tenant_settings.view';
    case TENANT_SETTINGS_UPDATE = 'tenant_settings.update';

    // 17. Subscription & Billing (Super Admin)
    case SUBSCRIPTIONS_VIEW = 'subscriptions.view';
    case SUBSCRIPTIONS_CREATE = 'subscriptions.create';
    case SUBSCRIPTIONS_UPDATE = 'subscriptions.update';
    case SUBSCRIPTIONS_DELETE = 'subscriptions.delete';
    case SUBSCRIPTIONS_MANAGE = 'subscriptions.manage';

    // 18. System Settings (Super Admin)
    case SYSTEM_SETTINGS_VIEW = 'system_settings.view';
    case SYSTEM_SETTINGS_UPDATE = 'system_settings.update';
    case SYSTEM_CURRENCIES_MANAGE = 'system.currencies.manage';
    case SYSTEM_TAX_CONFIG_MANAGE = 'system.tax_config.manage';

    /**
     * Get Module Category
     */
    public function module(): string
    {
        return match ($this) {
            self::DASHBOARD_VIEW, self::DASHBOARD_FINANCIAL_VIEW, self::DASHBOARD_BOOKINGS_VIEW => 'Dashboard',
            self::VENUES_VIEW, self::VENUES_CREATE, self::VENUES_UPDATE, self::VENUES_DELETE => 'Venues / Halls',
            self::SERVICES_VIEW, self::SERVICES_CREATE, self::SERVICES_UPDATE, self::SERVICES_DELETE => 'Services & Facilities',
            self::PACKAGES_VIEW, self::PACKAGES_MANAGE => 'Packages & Menus',
            self::BOOKINGS_VIEW, self::BOOKINGS_CREATE, self::BOOKINGS_UPDATE, self::BOOKINGS_DELETE, self::BOOKINGS_PRINT, self::BOOKINGS_STATUS_CHANGE, self::BOOKINGS_CALENDAR_VIEW, self::BOOKINGS_BLOCK_DATES => 'Bookings & Calendar',
            self::QUOTATIONS_VIEW, self::QUOTATIONS_CREATE, self::QUOTATIONS_UPDATE, self::QUOTATIONS_DELETE, self::QUOTATIONS_PRINT, self::QUOTATIONS_CONVERT => 'Quotations',
            self::CUSTOMERS_VIEW, self::CUSTOMERS_CREATE, self::CUSTOMERS_UPDATE, self::CUSTOMERS_DELETE, self::CUSTOMERS_EXPORT => 'Customers',
            self::INVOICES_VIEW, self::INVOICES_CREATE, self::INVOICES_UPDATE, self::INVOICES_DELETE, self::INVOICES_PRINT, self::INVOICES_DISCOUNT_APPLY => 'Invoices & Billing',
            self::PAYMENTS_VIEW, self::PAYMENTS_CREATE, self::PAYMENTS_UPDATE, self::PAYMENTS_DELETE, self::PAYMENTS_PRINT, self::PAYMENTS_REFUND => 'Payments',
            self::EXPENSES_VIEW, self::EXPENSES_CREATE, self::EXPENSES_UPDATE, self::EXPENSES_DELETE, self::EXPENSES_CATEGORIES => 'Expenses',
            self::FINANCE_VIEW, self::FINANCE_CREATE => 'Finance',
            self::VENDORS_VIEW, self::VENDORS_CREATE, self::VENDORS_UPDATE, self::VENDORS_DELETE => 'Vendors',
            self::LEDGERS_CUSTOMER_VIEW, self::LEDGERS_SUPPLIER_VIEW, self::ACCOUNTS_SUMMARY_VIEW => 'Accounts & Ledger',
            self::STAFF_VIEW, self::STAFF_CREATE, self::STAFF_UPDATE, self::STAFF_DELETE => 'Staff Management',
            self::ROLES_VIEW, self::ROLES_CREATE, self::ROLES_EDIT, self::ROLES_DELETE, self::ROLES_MANAGE => 'Roles & Permissions',
            self::REPORTS_BOOKINGS_VIEW, self::REPORTS_REVENUE_VIEW, self::REPORTS_EXPENSES_VIEW, self::REPORTS_OUTSTANDING_VIEW, self::REPORTS_PROFIT_LOSS_VIEW, self::REPORTS_EXPORT => 'Reports & Analytics',
            self::NOTIFICATIONS_WHATSAPP, self::NOTIFICATIONS_SMS => 'Notifications',
            self::TENANT_SETTINGS_VIEW, self::TENANT_SETTINGS_UPDATE => 'Tenant Settings',
            self::SUBSCRIPTIONS_VIEW, self::SUBSCRIPTIONS_CREATE, self::SUBSCRIPTIONS_UPDATE, self::SUBSCRIPTIONS_DELETE, self::SUBSCRIPTIONS_MANAGE => 'Subscription & Billing',
            self::SYSTEM_SETTINGS_VIEW, self::SYSTEM_SETTINGS_UPDATE, self::SYSTEM_CURRENCIES_MANAGE, self::SYSTEM_TAX_CONFIG_MANAGE => 'System Settings',
        };
    }

    /**
     * Get Module Icon
     */
    public static function moduleIcon(string $module): string
    {
        return match ($module) {
            'Dashboard' => 'icon-speedometer',
            'Venues / Halls' => 'ti-home',
            'Services & Facilities' => 'ti-layers',
            'Packages & Menus' => 'ti-package',
            'Bookings & Calendar' => 'ti-calendar',
            'Quotations' => 'ti-receipt',
            'Customers' => 'ti-user',
            'Invoices & Billing' => 'ti-file',
            'Payments' => 'ti-money',
            'Expenses' => 'ti-wallet',
            'Finance' => 'ti-money',
            'Vendors' => 'ti-truck',
            'Accounts & Ledger' => 'ti-book',
            'Staff Management' => 'ti-id-badge',
            'Roles & Permissions' => 'ti-lock',
            'Reports & Analytics' => 'ti-bar-chart',
            'Notifications' => 'ti-bell',
            'Tenant Settings' => 'ti-settings',
            'Subscription & Billing' => 'ti-credit-card',
            'System Settings' => 'ti-settings-alt',
            default => 'ti-folder',
        };
    }

    /**
     * Human Readable Label
     */
    public function label(): string
    {
        return match ($this) {
            self::DASHBOARD_VIEW => 'View Dashboard',
            self::DASHBOARD_FINANCIAL_VIEW => 'View Financial Statistics',
            self::DASHBOARD_BOOKINGS_VIEW => 'View Booking Statistics',

            self::VENUES_VIEW => 'View Venues / Halls',
            self::VENUES_CREATE => 'Create New Venue',
            self::VENUES_UPDATE => 'Edit Venue Details',
            self::VENUES_DELETE => 'Delete Venue',

            self::SERVICES_VIEW => 'View Services',
            self::SERVICES_CREATE => 'Add Service',
            self::SERVICES_UPDATE => 'Edit Service',
            self::SERVICES_DELETE => 'Delete Service',

            self::PACKAGES_VIEW => 'View Packages & Menus',
            self::PACKAGES_MANAGE => 'Manage Packages & Dishes',

            self::BOOKINGS_VIEW => 'View Bookings',
            self::BOOKINGS_CREATE => 'Create New Booking',
            self::BOOKINGS_UPDATE => 'Edit Booking',
            self::BOOKINGS_DELETE => 'Delete Booking',
            self::BOOKINGS_PRINT => 'Print Booking / Contract',
            self::BOOKINGS_STATUS_CHANGE => 'Change Booking Status (Confirm / Cancel)',
            self::BOOKINGS_CALENDAR_VIEW => 'View Booking Calendar',
            self::BOOKINGS_BLOCK_DATES => 'Block / Unblock Dates',

            self::QUOTATIONS_VIEW => 'View Quotations',
            self::QUOTATIONS_CREATE => 'Create Quotation',
            self::QUOTATIONS_UPDATE => 'Edit Quotation',
            self::QUOTATIONS_DELETE => 'Delete Quotation',
            self::QUOTATIONS_PRINT => 'Print Quotation',
            self::QUOTATIONS_CONVERT => 'Convert Quotation to Booking',

            self::CUSTOMERS_VIEW => 'View Customer List',
            self::CUSTOMERS_CREATE => 'Add Customer',
            self::CUSTOMERS_UPDATE => 'Edit Customer Profile',
            self::CUSTOMERS_DELETE => 'Delete Customer',
            self::CUSTOMERS_EXPORT => 'Export Customers Data',

            self::INVOICES_VIEW => 'View Invoices',
            self::INVOICES_CREATE => 'Generate Invoice',
            self::INVOICES_UPDATE => 'Edit Invoice',
            self::INVOICES_DELETE => 'Delete Invoice',
            self::INVOICES_PRINT => 'Print / Download Invoice',
            self::INVOICES_DISCOUNT_APPLY => 'Apply Special Discount',

            self::PAYMENTS_VIEW => 'View Payment Records',
            self::PAYMENTS_CREATE => 'Receive / Record Payment',
            self::PAYMENTS_UPDATE => 'Edit Payment Record',
            self::PAYMENTS_DELETE => 'Delete Payment',
            self::PAYMENTS_PRINT => 'Print Payment Receipt',
            self::PAYMENTS_REFUND => 'Process Payment Refund',

            self::EXPENSES_VIEW => 'View Business Expenses',
            self::EXPENSES_CREATE => 'Add Expense Entry',
            self::EXPENSES_UPDATE => 'Edit Expense',
            self::EXPENSES_DELETE => 'Delete Expense',
            self::EXPENSES_CATEGORIES => 'Manage Expense Categories',

            self::FINANCE_VIEW => 'View Finance Records',
            self::FINANCE_CREATE => 'Create Finance Transactions',

            self::VENDORS_VIEW => 'View Vendors',
            self::VENDORS_CREATE => 'Add Vendor',
            self::VENDORS_UPDATE => 'Edit Vendor',
            self::VENDORS_DELETE => 'Delete Vendor',

            self::LEDGERS_CUSTOMER_VIEW => 'View Customer Ledger',
            self::LEDGERS_SUPPLIER_VIEW => 'View Supplier / Vendor Ledger',
            self::ACCOUNTS_SUMMARY_VIEW => 'View Accounts & Balance Summary',

            self::STAFF_VIEW => 'View Staff Members',
            self::STAFF_CREATE => 'Add Staff Member',
            self::STAFF_UPDATE => 'Edit Staff & Assign Roles',
            self::STAFF_DELETE => 'Delete Staff Account',

            self::ROLES_VIEW => 'View Roles',
            self::ROLES_CREATE => 'Create Custom Role',
            self::ROLES_EDIT => 'Edit Role & Permissions',
            self::ROLES_DELETE => 'Delete Role',
            self::ROLES_MANAGE => 'Manage Roles & Permissions',

            self::REPORTS_BOOKINGS_VIEW => 'View Bookings Report',
            self::REPORTS_REVENUE_VIEW => 'View Revenue Report',
            self::REPORTS_EXPENSES_VIEW => 'View Expenses Report',
            self::REPORTS_OUTSTANDING_VIEW => 'View Outstanding Balances',
            self::REPORTS_PROFIT_LOSS_VIEW => 'View Profit & Loss Report',
            self::REPORTS_EXPORT => 'Export Reports (Excel / PDF)',

            self::NOTIFICATIONS_WHATSAPP => 'Send WhatsApp Alerts',
            self::NOTIFICATIONS_SMS => 'Send SMS Notifications',

            self::TENANT_SETTINGS_VIEW => 'View Business Settings',
            self::TENANT_SETTINGS_UPDATE => 'Update Business Settings & Taxes',

            self::SUBSCRIPTIONS_VIEW => 'View Subscriptions',
            self::SUBSCRIPTIONS_CREATE => 'Create Subscription Plan',
            self::SUBSCRIPTIONS_UPDATE => 'Edit Subscription',
            self::SUBSCRIPTIONS_DELETE => 'Delete Subscription',
            self::SUBSCRIPTIONS_MANAGE => 'Manage Subscriptions & Billing',

            self::SYSTEM_SETTINGS_VIEW => 'View System Settings',
            self::SYSTEM_SETTINGS_UPDATE => 'Update System Configuration',
            self::SYSTEM_CURRENCIES_MANAGE => 'Manage Currencies',
            self::SYSTEM_TAX_CONFIG_MANAGE => 'Configure Tax Settings',
        };
    }
}
