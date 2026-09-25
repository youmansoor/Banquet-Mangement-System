<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN ONLY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $user->role === 'super_admin',
            403
        );

        $notifications = $user
            ->unreadNotifications()
            ->latest()
            ->limit(20)
            ->get();

        $count = $user
            ->unreadNotifications()
            ->count();

        $messages = $notifications->map(
            function ($notification) {

                $data = $notification->data;

                return [

                    'id' => $notification->id,

                    'type' => $data['type']
                        ?? $notification->type,

                    'title' => $data['title']
                        ?? 'Notification',

                    'message' => $data['message']
                        ?? '',

                    'tenant_name' => $data['tenant_name']
                        ?? 'Tenant',

                    'customer_name' => $data['customer_name']
                        ?? null,

                    'amount' => $data['amount']
                        ?? null,

                    'booking_id' => $data['booking_id']
                        ?? null,

                    'url' => $data['url']
                        ?? '#',

                    'time' => $notification->created_at
                        ->diffForHumans(),
                ];
            }
        );

        return response()->json([
            'count' => $count,

            'messages' => $messages,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MARK AS READ
    |--------------------------------------------------------------------------
    */

    public function markAsRead(Request $request, string $id)
    {
        $user = $request->user();

        abort_unless(
            $user->role === 'super_admin',
            403
        );

        $notification = $user
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'success' => true,
        ]);
    }
}
