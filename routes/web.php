
<?php

use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\SubscriptionPaymentController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AdminTenantPaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingPaymentController;
use App\Http\Controllers\BookingReminderController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FreeServiceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LawnTypeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TenantBankController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TenantDashboardController;
use App\Http\Controllers\TenantFinanceController;
use App\Http\Controllers\TenantFinanceMasterController;
use App\Http\Controllers\TenantPagePinController;
use App\Http\Controllers\TenantProfileController;
use App\Http\Controllers\TenantRoleController;
use App\Http\Controllers\TenantSettingsController;
use App\Http\Controllers\TenantTermConditionController;
use App\Http\Controllers\TenantTermsController;
use App\Http\Controllers\TenantViewTermConditionsController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorPaymentController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\PermissionRegistrar;


/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::get('/login', [
    AuthController::class,
    'showLogin',
])->name('login');

Route::post('/login', [
    AuthController::class,
    'login',
])->name('login.post');

Route::post('/logout', [
    AuthController::class,
    'logout',
])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| SUPER ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Super Admin',
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | ADMIN DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [
            AdminDashboardController::class,
            'index',
        ])
            ->middleware('page.pin:dashboard')
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | TENANTS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'tenants',
            TenantController::class
        )
            ->middleware('page.pin:tenants');

        Route::patch('/tenants/{tenant}/toggle-status', [
            TenantController::class,
            'toggleStatus',
        ])
            ->middleware('page.pin:tenants')
            ->name('tenants.toggle-status');


        /*
        |--------------------------------------------------------------------------
        | SUBSCRIPTION PLANS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'subscription-plans',
            SubscriptionPlanController::class
        )
            ->middleware('page.pin:subscription_plans');


        /*
        |--------------------------------------------------------------------------
        | SUBSCRIPTIONS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'subscriptions',
            SubscriptionController::class
        )
            ->middleware('page.pin:subscriptions');


        /*
        |--------------------------------------------------------------------------
        | SUBSCRIPTION PAYMENTS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'subscription-payments',
            SubscriptionPaymentController::class
        )
            ->middleware('page.pin:subscription_payments');

        Route::get('/subscription-payments/get-tenant-details', [
            SubscriptionPaymentController::class,
            'getTenantDetails',
        ])->name('subscription-payments.get-tenant-details');


        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN BOOKINGS
        |--------------------------------------------------------------------------
        */

        Route::get('/bookings', [
            BookingController::class,
            'superAdminBookings',
        ])
            ->middleware('page.pin:bookings')
            ->name('bookings.index');

        Route::get('/bookings/tenant/{tenant}', [
            BookingController::class,
            'superAdminTenantBookings',
        ])
            ->middleware('page.pin:bookings')
            ->name('bookings.tenant');


        /*
        |--------------------------------------------------------------------------
        | TENANT SUBSCRIPTION PAYMENTS
        |--------------------------------------------------------------------------
        */

        Route::get('/tenant-payments', [
            AdminTenantPaymentController::class,
            'index',
        ])
            ->middleware('page.pin:payments')
            ->name('tenant-payments.index');

        Route::get('/tenant-payments/create', [
            AdminTenantPaymentController::class,
            'create',
        ])
            ->middleware('page.pin:payments')
            ->name('tenant-payments.create');

        Route::post('/tenant-payments', [
            AdminTenantPaymentController::class,
            'store',
        ])->name('tenant-payments.store');

        Route::get('/tenant-payments/{tenant}/history', [
            AdminTenantPaymentController::class,
            'history',
        ])
            ->middleware('page.pin:payments')
            ->name('tenant-payments.history');


        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN PAYMENTS
        |--------------------------------------------------------------------------
        */

        Route::get('/payments', [
            PaymentController::class,
            'index',
        ])
            ->middleware('page.pin:payments')
            ->name('payments.index');

        Route::post('/payments', [
            PaymentController::class,
            'store',
        ])->name('payments.store');


        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN ROLES
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'roles',
            RoleController::class
        )
            ->middleware('page.pin:roles');


        /*
        |--------------------------------------------------------------------------
        | ADMIN PROFILE
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [
            AdminDashboardController::class,
            'profile',
        ])->name('profile');

        Route::put('/profile', [
            AdminDashboardController::class,
            'updateProfile',
        ])->name('profile.update');


        /*
        |--------------------------------------------------------------------------
        | ADMIN PAGE PINS
        |--------------------------------------------------------------------------
        */

        Route::get('/profile/page-pins', [
            AdminDashboardController::class,
            'pagePins',
        ])->name('profile.page-pins');

        Route::put('/profile/page-pins', [
            AdminDashboardController::class,
            'updatePagePins',
        ])->name('profile.page-pins.update');

        Route::post('/profile/page-pins/status', [
            AdminDashboardController::class,
            'updatePagePinStatus',
        ])->name('profile.page-pins.status');

        Route::post('/profile/page-pins/verify', [
            AdminDashboardController::class,
            'verifyPagePin',
        ])->name('profile.page-pins.verify');


        /*
        |--------------------------------------------------------------------------
        | TENANT PAGE PINS - ADMIN MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::get('/profile/tenants/{tenant}/page-pins', [
            AdminDashboardController::class,
            'tenantPagePins',
        ])->name('profile.tenant-page-pins');

        Route::put('/profile/tenants/{tenant}/page-pins', [
            AdminDashboardController::class,
            'updateTenantPagePins',
        ])->name('profile.tenant-page-pins.update');

        Route::post('/profile/tenants/{tenant}/page-pins/status', [
            AdminDashboardController::class,
            'updateTenantPagePinStatus',
        ])->name('profile.tenant-page-pins.status');

    });


/*
|--------------------------------------------------------------------------
| TENANT PAGE PIN VERIFICATION
|--------------------------------------------------------------------------
*/

Route::post('/tenant/page-pins/verify', [
    TenantPagePinController::class,
    'verify',
])
    ->middleware('auth')
    ->name('tenant.page-pins.verify');


/*
|--------------------------------------------------------------------------
| TENANT MAIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
    'tenant.booking.reminder',
])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/tenant/dashboard', [
            TenantDashboardController::class,
            'index',
        ])
            ->middleware('tenant.terms')
            ->name('tenant.dashboard');


        /*
        |--------------------------------------------------------------------------
        | CUSTOMERS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'customers',
            CustomerController::class
        )
            ->middleware('tenant.page.pin:customers');


        /*
        |--------------------------------------------------------------------------
        | LAWN TYPES
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'lawn_types',
            LawnTypeController::class
        )
            ->parameters([
                'lawn_types' => 'lawnType',
            ])
            ->names([
                'index' => 'lawn_types.index',
                'create' => 'lawn_types.create',
                'store' => 'lawn_types.store',
                'edit' => 'lawn_types.edit',
                'update' => 'lawn_types.update',
                'destroy' => 'lawn_types.destroy',
            ])
            ->middleware('tenant.page.pin:lawn_types');


        /*
        |--------------------------------------------------------------------------
        | BOOKING AVAILABILITY
        |--------------------------------------------------------------------------
        */

        Route::get('/bookings/check-availability', [
            BookingController::class,
            'checkAvailability',
        ])
            ->middleware('tenant.page.pin:bookings')
            ->name('bookings.checkAvailability');


        /*
        |--------------------------------------------------------------------------
        | BOOKING CALENDAR
        |--------------------------------------------------------------------------
        */

        Route::get('/bookings/calendar', [
            BookingController::class,
            'calendar',
        ])
            ->middleware('tenant.page.pin:bookings')
            ->name('bookings.calendar');

        Route::get('/bookings/calendar/events', [
            BookingController::class,
            'calendarEvents',
        ])->name('bookings.calendar.events');


        /*
        |--------------------------------------------------------------------------
        | BOOKING PAYMENT HISTORY
        |--------------------------------------------------------------------------
        */

        Route::get('/bookings/{id}/payments', [
            BookingController::class,
            'paymentHistory',
        ])
            ->middleware('tenant.page.pin:payments')
            ->name('bookings.payments.history');

        Route::post('/bookings/{id}/payments', [
            BookingController::class,
            'storeInstallment',
        ])
            ->middleware('tenant.page.pin:payments')
            ->name('bookings.payments.store');


        /*
        |--------------------------------------------------------------------------
        | BOOKING REMINDERS
        |--------------------------------------------------------------------------
        */

        Route::get('/reminders', [
            BookingReminderController::class,
            'index',
        ])
            ->middleware('tenant.page.pin:bookings')
            ->name('bookings.reminders');

        Route::post('/bookings/{booking}/remind', [
            BookingReminderController::class,
            'sendReminder',
        ])
            ->middleware('tenant.page.pin:bookings')
            ->name('bookings.remind');


        /*
        |--------------------------------------------------------------------------
        | BOOKINGS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'bookings',
            BookingController::class
        )
            ->middleware('tenant.page.pin:bookings');

        Route::post('/bookings/{booking}/cancel', [
            BookingController::class,
            'cancel',
        ])
            ->middleware('tenant.page.pin:bookings')
            ->name('bookings.cancel');

        Route::get('/cancelled-bookings', [
            BookingController::class,
            'cancelledBookings',
        ])
            ->middleware('tenant.page.pin:bookings')
            ->name('bookings.cancelled');


        /*
        |--------------------------------------------------------------------------
        | QUOTATIONS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'quotations',
            QuotationController::class
        )
            ->middleware('tenant.page.pin:quotations');

        Route::post('/quotations/{quotation}/convert-to-booking', [
            QuotationController::class,
            'convertToBooking',
        ])
            ->middleware('tenant.page.pin:quotations')
            ->name('quotations.convert-to-booking');

        Route::post('/quotations/{quotation}/generate-invoice', [
            QuotationController::class,
            'generateInvoice',
        ])
            ->middleware('tenant.page.pin:quotations')
            ->name('quotations.generateInvoice');


        /*
        |--------------------------------------------------------------------------
        | SERVICES
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'services',
            ServiceController::class
        )
            ->middleware('tenant.page.pin:services');


        /*
        |--------------------------------------------------------------------------
        | FREE SERVICES
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'free-services',
            FreeServiceController::class
        )
            ->middleware('tenant.page.pin:free_services');


        /*
        |--------------------------------------------------------------------------
        | TENANT PAYMENTS
        |--------------------------------------------------------------------------
        */

        Route::get('/payments', [
            PaymentController::class,
            'index',
        ])
            ->middleware('tenant.page.pin:payments')
            ->name('tenant.payments.index');

        Route::post('/payments', [
            PaymentController::class,
            'store',
        ])->name('tenant.payments.store');


        /*
        |--------------------------------------------------------------------------
        | INVOICES
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'invoices',
            InvoiceController::class
        )
            ->middleware('tenant.page.pin:invoices');

        Route::get('/invoices/{invoice}/print', [
            InvoiceController::class,
            'print',
        ])
            ->middleware('tenant.page.pin:invoices')
            ->name('invoices.print');


        /*
        |--------------------------------------------------------------------------
        | TENANT PROFILE
        |--------------------------------------------------------------------------
        */

        Route::get('/tenant/profile', [
            TenantProfileController::class,
            'edit',
        ])
            ->middleware('tenant.page.pin:profile')
            ->name('tenant.profile.edit');

        Route::put('/tenant/profile', [
            TenantProfileController::class,
            'update',
        ])
            ->middleware('tenant.page.pin:profile')
            ->name('tenant.profile.update');


        /*
        |--------------------------------------------------------------------------
        | TENANT SETTINGS
        |--------------------------------------------------------------------------
        */

        Route::get('/tenant/settings', [
            TenantSettingsController::class,
            'index',
        ])
            ->middleware('tenant.page.pin:settings')
            ->name('tenant.settings');

        Route::put('/tenant/settings', [
            TenantSettingsController::class,
            'update',
        ])
            ->middleware('tenant.page.pin:settings')
            ->name('tenant.settings.update');

    });


/*
|--------------------------------------------------------------------------
| TENANT ROLES AND STAFF
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
    'tenant.booking.reminder',
])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {

        Route::resource(
            'roles',
            TenantRoleController::class
        )
            ->middleware('tenant.page.pin:roles');

        Route::resource(
            'staff',
            StaffController::class
        )
            ->middleware('tenant.page.pin:staff');

    });


/*
|--------------------------------------------------------------------------
| TENANT CHAT
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {

        Route::get('/chat', [
            ChatController::class,
            'tenantChat',
        ])->name('chat');

        Route::post('/chat/{conversation}/send', [
            ChatController::class,
            'send',
        ])->name('chat.send');

        Route::post('/chat/{conversation}/read', [
            ChatController::class,
            'markAsRead',
        ])->name('chat.read');

    });


/*
|--------------------------------------------------------------------------
| SHARED CHAT READ
|--------------------------------------------------------------------------
*/

Route::post('/chat/{conversation}/read', [
    ChatController::class,
    'markAsRead',
])
    ->middleware('auth')
    ->name('chat.read');


/*
|--------------------------------------------------------------------------
| NOTIFICATIONS
|--------------------------------------------------------------------------
*/

Route::get('/tenant/notifications', [
    ChatController::class,
    'tenantNotifications',
])
    ->middleware([
        'auth',
        'tenant',
    ])
    ->name('tenant.notifications');

Route::get('/admin/chat/notifications', [
    ChatController::class,
    'adminNotifications',
])
    ->middleware('auth')
    ->name('admin.chat.notifications');


/*
|--------------------------------------------------------------------------
| SUPER ADMIN CHAT
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Super Admin',
])
    ->prefix('admin/chat')
    ->name('admin.chat.')
    ->group(function () {

        Route::get('/', [
            ChatController::class,
            'adminChats',
        ])->name('index');

        Route::get('/conversation/{conversation}', [
            ChatController::class,
            'adminConversation',
        ])->name('conversation');

        Route::get('/conversation/{conversation}/messages', [
            ChatController::class,
            'adminConversationMessages',
        ])->name('messages');

        Route::post('/conversation/{conversation}/send', [
            ChatController::class,
            'adminSendMessage',
        ])->name('send');

    });


/*
|--------------------------------------------------------------------------
| ADMIN NOTIFICATIONS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/notifications', [
            AdminNotificationController::class,
            'index',
        ])->name('notifications');

        Route::post('/notifications/{id}/read', [
            AdminNotificationController::class,
            'markAsRead',
        ])->name('notifications.read');

    });


/*
|--------------------------------------------------------------------------
| TEST ROLE / PERMISSION DEBUG
|--------------------------------------------------------------------------
*/

Route::get('/test-role', function () {

    $user = auth()->user();

    abort_unless($user, 401);

    if ($user->tenant_id !== null) {
        app(PermissionRegistrar::class)
            ->setPermissionsTeamId($user->tenant_id);
    }

    app(PermissionRegistrar::class)
        ->forgetCachedPermissions();

    $user->refresh();

    return [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'tenant_id' => $user->tenant_id,
        'role_column' => $user->role,
        'roles' => $user->getRoleNames()->toArray(),
        'permissions' => $user->getPermissionNames()->toArray(),
        'dashboard_view' => $user->can('dashboard.view'),
        'tenant_dashboard_view' => $user->can('tenant.dashboard.view'),
        'is_super_admin' => $user->hasRole('Super Admin'),
        'current_team_id' => app(PermissionRegistrar::class)
            ->getPermissionsTeamId(),
    ];

})->middleware('auth');


/*
|--------------------------------------------------------------------------
| ADMIN INVOICES
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Super Admin',
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/invoices', [
            AdminTenantPaymentController::class,
            'invoices',
        ])
            ->middleware('page.pin:payments')
            ->name('invoices.index');

        Route::get('/invoices/create', [
            AdminTenantPaymentController::class,
            'createInvoice',
        ])
            ->middleware('page.pin:payments')
            ->name('invoices.create');

        Route::get('/invoices/{payment}/print', [
            AdminTenantPaymentController::class,
            'printInvoice',
        ])
            ->middleware('page.pin:payments')
            ->name('invoices.print');

    });


/*
|--------------------------------------------------------------------------
| TENANT FINANCE
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
    'tenant.booking.reminder',
])
    ->group(function () {

        Route::get('/tenant/finance', [
            TenantFinanceController::class,
            'create',
        ])
            ->middleware('tenant.page.pin:payments')
            ->name('tenant.finance.create');

        Route::post('/tenant/finance', [
            TenantFinanceController::class,
            'store',
        ])
            ->middleware('tenant.page.pin:payments')
            ->name('tenant.finance.store');

        Route::get('/tenant/finance/customer/{customer}/jobs', [
            TenantFinanceController::class,
            'customerJobs',
        ])
            ->middleware('tenant.page.pin:payments')
            ->name('tenant.finance.customer.jobs');

        Route::get('/tenant/finance/booking/{booking}/bills', [
            TenantFinanceController::class,
            'bookingBills',
        ])
            ->middleware('tenant.page.pin:payments')
            ->name('tenant.finance.booking.bills');


        /*
        |--------------------------------------------------------------------------
        | EXPENSE CREATE AND STORE
        |--------------------------------------------------------------------------
        */

        Route::get('/expenses/create', [
            ExpenseController::class,
            'create',
        ])
            ->middleware('tenant.page.pin:expenses')
            ->name('expenses.create');

        Route::post('/expenses', [
            ExpenseController::class,
            'store',
        ])
            ->middleware('tenant.page.pin:expenses')
            ->name('expenses.store');


        /*
        |--------------------------------------------------------------------------
        | VENDOR PAYMENTS
        |--------------------------------------------------------------------------
        */

        Route::get('/finance/vendor-payments/create', [
            VendorPaymentController::class,
            'create',
        ])
            ->middleware('tenant.page.pin:vendor_payments')
            ->name('vendor.payments.create');

        Route::post('/finance/vendor-payments', [
            VendorPaymentController::class,
            'store',
        ])
            ->middleware('tenant.page.pin:vendor_payments')
            ->name('vendor.payments.store');


        /*
        |--------------------------------------------------------------------------
        | BOOKING PAYMENTS
        |--------------------------------------------------------------------------
        */

        Route::get('/finance/booking-payments/create', [
            BookingPaymentController::class,
            'create',
        ])
            ->middleware('tenant.page.pin:booking_payments')
            ->name('booking.payments.create');

        Route::post('/finance/booking-payments', [
            BookingPaymentController::class,
            'store',
        ])
            ->middleware('tenant.page.pin:booking_payments')
            ->name('booking.payments.store');

    });


/*
|--------------------------------------------------------------------------
| BOOKING PAYMENT RECEIPT
|--------------------------------------------------------------------------
*/

Route::get('/bookings/payment-receipts/{receipt}/print', [
    BookingController::class,
    'printPaymentReceipt',
])
    ->middleware('auth')
    ->name('bookings.payment.receipt.print');


/*
|--------------------------------------------------------------------------
| BOOKING PRINT
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
])
    ->group(function () {

        Route::get('/bookings/{booking}/print', [
            BookingController::class,
            'print',
        ])
            ->middleware('tenant.page.pin:bookings')
            ->name('bookings.print');

    });


/*
|--------------------------------------------------------------------------
| TENANT TERMS ACCEPTANCE
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
])
    ->group(function () {

        Route::get('/tenant/terms', [
            TenantTermsController::class,
            'index',
        ])->name('tenant.terms');

        Route::post('/tenant/terms-conditions/accept', [
            TenantTermsController::class,
            'accept',
        ])->name('tenant.terms.accept');

    });


/*
|--------------------------------------------------------------------------
| TENANT VENDORS
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
    'tenant.booking.reminder',
])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {

        Route::resource(
            'vendors',
            VendorController::class
        )
            ->middleware('tenant.page.pin:vendors');

        Route::patch('/vendors/{vendor}/toggle-status', [
            VendorController::class,
            'toggleStatus',
        ])
            ->middleware('tenant.page.pin:vendors')
            ->name('vendors.toggle-status');

    });


/*
|--------------------------------------------------------------------------
| CALENDAR
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
])
    ->group(function () {

        Route::get('/calendar', [
            CalendarController::class,
            'index',
        ])
            ->middleware('tenant.page.pin:bookings')
            ->name('calendar.index');

    });


/*
|--------------------------------------------------------------------------
| EXPENSE MANAGEMENT FULL CRUD
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
    'tenant.booking.reminder',
])
    ->group(function () {

        Route::get('/expenses', [
            ExpenseController::class,
            'index',
        ])
            ->middleware('tenant.page.pin:expenses')
            ->name('expenses.index');

        Route::get('/expenses/{expense}', [
            ExpenseController::class,
            'show',
        ])
            ->middleware('tenant.page.pin:expenses')
            ->name('expenses.show');

        Route::get('/expenses/{expense}/edit', [
            ExpenseController::class,
            'edit',
        ])
            ->middleware('tenant.page.pin:expenses')
            ->name('expenses.edit');

        Route::put('/expenses/{expense}', [
            ExpenseController::class,
            'update',
        ])
            ->middleware('tenant.page.pin:expenses')
            ->name('expenses.update');

        Route::delete('/expenses/{expense}', [
            ExpenseController::class,
            'destroy',
        ])
            ->middleware('tenant.page.pin:expenses')
            ->name('expenses.destroy');

    });


/*
|--------------------------------------------------------------------------
| BANKS CRUD
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {

        Route::resource(
            'banks',
            TenantBankController::class
        )
            ->middleware('tenant.page.pin:banks');

    });


/*
|--------------------------------------------------------------------------
| FINANCE MASTERS CRUD
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {

        Route::get('finance-masters', [
            TenantFinanceMasterController::class,
            'index',
        ])->name('finance-masters.index');

        Route::get('finance-masters/create/{type}', [
            TenantFinanceMasterController::class,
            'create',
        ])->name('finance-masters.create');

        Route::post('finance-masters/store/{type}', [
            TenantFinanceMasterController::class,
            'store',
        ])->name('finance-masters.store');

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT: STATIC ROUTE BEFORE DYNAMIC ROUTES
        |--------------------------------------------------------------------------
        */

        Route::post('finance-masters/bulk-update', [
            TenantFinanceMasterController::class,
            'bulkUpdate',
        ])->name('finance-masters.bulk-update');

        Route::get('finance-masters/{master}', [
            TenantFinanceMasterController::class,
            'show',
        ])->name('finance-masters.show');

        Route::get('finance-masters/{master}/edit', [
            TenantFinanceMasterController::class,
            'edit',
        ])->name('finance-masters.edit');

        Route::put('finance-masters/{master}', [
            TenantFinanceMasterController::class,
            'update',
        ])->name('finance-masters.update');

        Route::delete('finance-masters/{master}', [
            TenantFinanceMasterController::class,
            'destroy',
        ])->name('finance-masters.destroy');

    });


/*
|--------------------------------------------------------------------------
| ACCEPTED TERMS AND CONDITIONS
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
])
    ->group(function () {

        Route::get('/tenant/accepted-terms-conditions', [
            TenantViewTermConditionsController::class,
            'index',
        ])->name('tenant.accepted-terms-conditions.index');

    });


/*
|--------------------------------------------------------------------------
| TERMS AND CONDITIONS CRUD
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'tenant',
])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {

        Route::resource(
            'terms-conditions',
            TenantTermConditionController::class
        )
            ->parameters([
                'terms-conditions' => 'term',
            ])
            ->middleware('tenant.page.pin:terms-conditions');

    });