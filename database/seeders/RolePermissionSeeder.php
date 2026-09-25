<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Reset Permission Cache
        |--------------------------------------------------------------------------
        */

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Global Permission Context
        |--------------------------------------------------------------------------
        |
        | These are template roles. They are not assigned directly to tenant
        | users. Tenant-specific roles will be created by TenantController.
        |
        */

        app(PermissionRegistrar::class)
            ->setPermissionsTeamId(null);

        /*
        |--------------------------------------------------------------------------
        | 1. CREATE ALL PERMISSIONS
        |--------------------------------------------------------------------------
        */

        foreach (PermissionEnum::cases() as $permissionEnum) {

            Permission::firstOrCreate([
                'name' => $permissionEnum->value,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Helper: Get Permission Models
        |--------------------------------------------------------------------------
        */

        $permissions = function (array $permissionEnums) {
            return collect($permissionEnums)
                ->map(function ($permissionEnum) {
                    return Permission::findByName(
                        $permissionEnum->value,
                        'web'
                    );
                })
                ->all();
        };

        /*
        |--------------------------------------------------------------------------
        | 1. SUPER ADMIN
        |--------------------------------------------------------------------------
        |
        | Platform owner with full system access.
        |
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
            'tenant_id' => null,
        ]);

        $superAdmin->syncPermissions(
            Permission::where('guard_name', 'web')->get()
        );

        /*
        |--------------------------------------------------------------------------
        | 2. TENANT OWNER
        |--------------------------------------------------------------------------
        |
        | Template role.
        |
        */

        $tenantOwner = Role::firstOrCreate([
            'name' => 'tenant_owner',
            'guard_name' => 'web',
            'tenant_id' => null,
        ]);

        $tenantOwner->syncPermissions(
            Permission::where('guard_name', 'web')->get()
        );

        /*
        |--------------------------------------------------------------------------
        | 3. BRANCH / GENERAL MANAGER
        |--------------------------------------------------------------------------
        */

        $manager = Role::firstOrCreate([
            'name' => 'branch_manager',
            'guard_name' => 'web',
            'tenant_id' => null,
        ]);

        $manager->syncPermissions(
            $permissions([
                PermissionEnum::DASHBOARD_VIEW,
                PermissionEnum::DASHBOARD_BOOKINGS_VIEW,
                PermissionEnum::DASHBOARD_FINANCIAL_VIEW,

                PermissionEnum::VENUES_VIEW,
                PermissionEnum::VENUES_CREATE,
                PermissionEnum::VENUES_UPDATE,

                PermissionEnum::SERVICES_VIEW,
                PermissionEnum::SERVICES_CREATE,
                PermissionEnum::SERVICES_UPDATE,

                PermissionEnum::BOOKINGS_VIEW,
                PermissionEnum::BOOKINGS_CREATE,
                PermissionEnum::BOOKINGS_UPDATE,
                PermissionEnum::BOOKINGS_PRINT,
                PermissionEnum::BOOKINGS_STATUS_CHANGE,
                PermissionEnum::BOOKINGS_CALENDAR_VIEW,
                PermissionEnum::BOOKINGS_BLOCK_DATES,

                PermissionEnum::PACKAGES_VIEW,
                PermissionEnum::PACKAGES_MANAGE,

                PermissionEnum::QUOTATIONS_VIEW,
                PermissionEnum::QUOTATIONS_CREATE,
                PermissionEnum::QUOTATIONS_UPDATE,
                PermissionEnum::QUOTATIONS_PRINT,
                PermissionEnum::QUOTATIONS_CONVERT,

                PermissionEnum::CUSTOMERS_VIEW,
                PermissionEnum::CUSTOMERS_CREATE,
                PermissionEnum::CUSTOMERS_UPDATE,
                PermissionEnum::CUSTOMERS_EXPORT,

                PermissionEnum::INVOICES_VIEW,
                PermissionEnum::INVOICES_CREATE,
                PermissionEnum::INVOICES_UPDATE,
                PermissionEnum::INVOICES_PRINT,

                PermissionEnum::PAYMENTS_VIEW,
                PermissionEnum::PAYMENTS_CREATE,
                PermissionEnum::PAYMENTS_PRINT,

                PermissionEnum::EXPENSES_VIEW,
                PermissionEnum::EXPENSES_CREATE,

                PermissionEnum::FINANCE_VIEW,
                PermissionEnum::FINANCE_CREATE,

                PermissionEnum::VENDORS_VIEW,
                PermissionEnum::VENDORS_CREATE,
                PermissionEnum::VENDORS_UPDATE,

                PermissionEnum::STAFF_VIEW,
                PermissionEnum::STAFF_CREATE,
                PermissionEnum::STAFF_UPDATE,

                PermissionEnum::ROLES_VIEW,

                PermissionEnum::REPORTS_BOOKINGS_VIEW,
                PermissionEnum::REPORTS_REVENUE_VIEW,
                PermissionEnum::REPORTS_OUTSTANDING_VIEW,

                PermissionEnum::NOTIFICATIONS_WHATSAPP,
                PermissionEnum::NOTIFICATIONS_SMS,
            ])
        );

        /*
        |--------------------------------------------------------------------------
        | 4. SALES OFFICER
        |--------------------------------------------------------------------------
        */

        $salesOfficer = Role::firstOrCreate([
            'name' => 'sales_officer',
            'guard_name' => 'web',
            'tenant_id' => null,
        ]);

        $salesOfficer->syncPermissions(
            $permissions([
                PermissionEnum::DASHBOARD_VIEW,
                PermissionEnum::DASHBOARD_BOOKINGS_VIEW,

                PermissionEnum::VENUES_VIEW,

                PermissionEnum::BOOKINGS_VIEW,
                PermissionEnum::BOOKINGS_CREATE,
                PermissionEnum::BOOKINGS_UPDATE,
                PermissionEnum::BOOKINGS_PRINT,
                PermissionEnum::BOOKINGS_CALENDAR_VIEW,

                PermissionEnum::PACKAGES_VIEW,

                PermissionEnum::QUOTATIONS_VIEW,
                PermissionEnum::QUOTATIONS_CREATE,
                PermissionEnum::QUOTATIONS_UPDATE,
                PermissionEnum::QUOTATIONS_PRINT,
                PermissionEnum::QUOTATIONS_CONVERT,

                PermissionEnum::CUSTOMERS_VIEW,
                PermissionEnum::CUSTOMERS_CREATE,
                PermissionEnum::CUSTOMERS_UPDATE,

                PermissionEnum::INVOICES_VIEW,
                PermissionEnum::INVOICES_PRINT,

                PermissionEnum::PAYMENTS_VIEW,
                PermissionEnum::PAYMENTS_CREATE,
                PermissionEnum::PAYMENTS_PRINT,

                PermissionEnum::FINANCE_VIEW,
                PermissionEnum::FINANCE_CREATE,

                PermissionEnum::VENDORS_VIEW,

                PermissionEnum::NOTIFICATIONS_WHATSAPP,
            ])
        );

        /*
        |--------------------------------------------------------------------------
        | 5. ACCOUNTANT / CASHIER
        |--------------------------------------------------------------------------
        */

        $accountant = Role::firstOrCreate([
            'name' => 'accountant',
            'guard_name' => 'web',
            'tenant_id' => null,
        ]);

        $accountant->syncPermissions(
            $permissions([
                PermissionEnum::DASHBOARD_VIEW,
                PermissionEnum::DASHBOARD_FINANCIAL_VIEW,

                PermissionEnum::BOOKINGS_VIEW,
                PermissionEnum::BOOKINGS_PRINT,

                PermissionEnum::CUSTOMERS_VIEW,

                PermissionEnum::INVOICES_VIEW,
                PermissionEnum::INVOICES_CREATE,
                PermissionEnum::INVOICES_UPDATE,
                PermissionEnum::INVOICES_PRINT,
                PermissionEnum::INVOICES_DISCOUNT_APPLY,

                PermissionEnum::PAYMENTS_VIEW,
                PermissionEnum::PAYMENTS_CREATE,
                PermissionEnum::PAYMENTS_UPDATE,
                PermissionEnum::PAYMENTS_PRINT,
                PermissionEnum::PAYMENTS_REFUND,

                PermissionEnum::EXPENSES_VIEW,
                PermissionEnum::EXPENSES_CREATE,
                PermissionEnum::EXPENSES_UPDATE,
                PermissionEnum::EXPENSES_CATEGORIES,

                PermissionEnum::FINANCE_VIEW,
                PermissionEnum::FINANCE_CREATE,

                PermissionEnum::VENDORS_VIEW,
                PermissionEnum::VENDORS_CREATE,
                PermissionEnum::VENDORS_UPDATE,

                PermissionEnum::LEDGERS_CUSTOMER_VIEW,
                PermissionEnum::LEDGERS_SUPPLIER_VIEW,

                PermissionEnum::ACCOUNTS_SUMMARY_VIEW,

                PermissionEnum::REPORTS_REVENUE_VIEW,
                PermissionEnum::REPORTS_EXPENSES_VIEW,
                PermissionEnum::REPORTS_OUTSTANDING_VIEW,
                PermissionEnum::REPORTS_PROFIT_LOSS_VIEW,
                PermissionEnum::REPORTS_EXPORT,
            ])
        );

        /*
        |--------------------------------------------------------------------------
        | 6. RECEPTIONIST
        |--------------------------------------------------------------------------
        */

        $receptionist = Role::firstOrCreate([
            'name' => 'receptionist',
            'guard_name' => 'web',
            'tenant_id' => null,
        ]);

        $receptionist->syncPermissions(
            $permissions([
                PermissionEnum::DASHBOARD_VIEW,
                PermissionEnum::DASHBOARD_BOOKINGS_VIEW,

                PermissionEnum::VENUES_VIEW,

                PermissionEnum::BOOKINGS_VIEW,
                PermissionEnum::BOOKINGS_CALENDAR_VIEW,

                PermissionEnum::CUSTOMERS_VIEW,
                PermissionEnum::CUSTOMERS_CREATE,
                PermissionEnum::CUSTOMERS_UPDATE,

                PermissionEnum::QUOTATIONS_VIEW,
                PermissionEnum::QUOTATIONS_PRINT,
            ])
        );

        /*
        |--------------------------------------------------------------------------
        | 7. VENDOR MANAGER
        |--------------------------------------------------------------------------
        */

        $vendorManager = Role::firstOrCreate([
            'name' => 'vendor_manager',
            'guard_name' => 'web',
            'tenant_id' => null,
        ]);

        $vendorManager->syncPermissions(
            $permissions([
                PermissionEnum::DASHBOARD_VIEW,

                PermissionEnum::BOOKINGS_VIEW,
                PermissionEnum::BOOKINGS_CALENDAR_VIEW,

                PermissionEnum::PACKAGES_VIEW,
                PermissionEnum::PACKAGES_MANAGE,

                PermissionEnum::EXPENSES_VIEW,
                PermissionEnum::EXPENSES_CREATE,

                PermissionEnum::FINANCE_VIEW,
                PermissionEnum::FINANCE_CREATE,

                PermissionEnum::VENDORS_VIEW,
                PermissionEnum::VENDORS_CREATE,
                PermissionEnum::VENDORS_UPDATE,

                PermissionEnum::LEDGERS_SUPPLIER_VIEW,
            ])
        );

        /*
        |--------------------------------------------------------------------------
        | 8. Clear Permission Cache Again
        |--------------------------------------------------------------------------
        */

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }
}
