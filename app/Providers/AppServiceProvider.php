<?php

namespace App\Providers;

use App\Models\Conversation;
use App\Models\LawnType;
use App\Models\Service;
use App\Models\TenantSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN + TENANT OWNER BYPASS
        |--------------------------------------------------------------------------
        |
        | Super Admin:
        |   - No tenant
        |   - Full system access
        |
        | Tenant Owner:
        |   - Belongs to a tenant
        |   - Full access inside own tenant
        |   - Does NOT need a Spatie role
        |
        | Other tenant staff:
        |   - Must use tenant-created Spatie roles
        |   - Permissions are checked normally
        |
        */

        Gate::before(function ($user, $ability) {

            /*
            |--------------------------------------------------------------------------
            | SUPER ADMIN
            |--------------------------------------------------------------------------
            */

            if ($user->isSuperAdmin()) {
                return true;
            }

            /*
            |--------------------------------------------------------------------------
            | TENANT OWNER
            |--------------------------------------------------------------------------
            */

            if (
                $user->role === 'tenant_owner'
                && ! is_null($user->tenant_id)
            ) {
                return true;
            }

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            |
            | Returning null allows Laravel/Spatie to continue with
            | the normal permission check.
            |
            */

            return null;
        });

        /*
        |--------------------------------------------------------------------------
        | TENANT NAV + CHAT DATA
        |--------------------------------------------------------------------------
        */

        View::composer('tenant.nav', function ($view) {

            $lawnTypes = collect();
            $services = collect();
            $settings = null;

            $tenantChatConversation = null;
            $tenantChatMessages = collect();
            $tenantChatUnreadCount = 0;

            if (Auth::check()) {

                $user = Auth::user();

                /*
                |--------------------------------------------------------------------------
                | Tenant Data
                |--------------------------------------------------------------------------
                */

                if ($user->tenant_id) {

                    $tenantId = $user->tenant_id;

                    /*
                    |--------------------------------------------------------------------------
                    | Lawn Types
                    |--------------------------------------------------------------------------
                    */

                    $lawnTypes = LawnType::where(
                        'tenant_id',
                        $tenantId
                    )
                        ->orderBy('lawn_type')
                        ->get();

                    /*
                    |--------------------------------------------------------------------------
                    | Services
                    |--------------------------------------------------------------------------
                    */

                    $services = Service::where(
                        'tenant_id',
                        $tenantId
                    )
                        ->orderBy('service_name')
                        ->get();

                    /*
                    |--------------------------------------------------------------------------
                    | Tenant Settings
                    |--------------------------------------------------------------------------
                    */

                    $settings = TenantSetting::firstOrCreate(
                        [
                            'tenant_id' => $tenantId,
                        ],
                        [
                            'business_name' => '',
                            'business_phone' => '',
                            'business_email' => '',
                            'business_address' => '',
                            'currency' => 'PKR',
                            'tax_enabled' => false,
                            'tax_name' => 'GST',
                            'tax_percentage' => 0,
                            'payment_method' => 'cash',
                            'booking_advance_percentage' => 0,
                        ]
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | TENANT CHAT
                    |--------------------------------------------------------------------------
                    */

                    $tenantChatConversation = Conversation::firstOrCreate(
                        [
                            'tenant_id' => $tenantId,
                        ],
                        [
                            'created_by' => $user->id,
                        ]
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Chat Messages
                    |--------------------------------------------------------------------------
                    */

                    $tenantChatMessages = $tenantChatConversation
                        ->messages()
                        ->with('sender')
                        ->orderBy('created_at', 'asc')
                        ->get();

                    /*
                    |--------------------------------------------------------------------------
                    | UNREAD ADMIN MESSAGES
                    |--------------------------------------------------------------------------
                    */

                    $tenantChatUnreadCount = $tenantChatConversation
                        ->messages()
                        ->whereNull('read_at')
                        ->where(
                            'sender_id',
                            '!=',
                            $user->id
                        )
                        ->count();
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Share Data With Tenant Navigation
            |--------------------------------------------------------------------------
            */

            $view->with([
                'lawnTypes' => $lawnTypes,
                'services' => $services,
                'settings' => $settings,

                'tenantChatConversation' => $tenantChatConversation,
                'tenantChatMessages' => $tenantChatMessages,
                'tenantChatUnreadCount' => $tenantChatUnreadCount,
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | ADMIN NAV + CHAT DATA
        |--------------------------------------------------------------------------
        */

        View::composer('admin.nav', function ($view) {

            $adminChatConversations = collect();

            if (
                Auth::check()
                && Auth::user()->isSuperAdmin()
            ) {

                $adminChatConversations = Conversation::with([
                    'tenant',

                    'messages' => function ($query) {
                        $query
                            ->latest()
                            ->limit(1);
                    },
                ])
                    ->withCount([
                        'messages as unread_messages_count' => function ($query) {

                            $query
                                ->whereNull('read_at')
                                ->whereHas('sender', function ($q) {
                                    $q->where(
                                        'role',
                                        '!=',
                                        'super_admin'
                                    );
                                });
                        },
                    ])
                    ->orderByDesc('last_message_at')
                    ->get();
            }

            /*
            |--------------------------------------------------------------------------
            | Share Admin Chat Data
            |--------------------------------------------------------------------------
            */

            $view->with([
                'adminChatConversations' => $adminChatConversations,
            ]);
        });
    }
}
