<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Tenant chat with Super Admin
     */
    public function tenantChat()
    {
        $user = Auth::user();

        abort_unless($user->tenant_id, 403);

        $conversation = Conversation::firstOrCreate(
            [
                'tenant_id' => $user->tenant_id,
            ],
            [
                'created_by' => $user->id,
            ]
        );

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('tenant.chat.index', compact(
            'conversation',
            'messages'
        ));
    }

    /**
     * Super Admin chat list
     */
    public function adminChats()
    {
        $conversations = Conversation::with('tenant')
            ->withCount([
                'messages as unread_messages_count' => function ($query) {
                    $query->whereNull('read_at')
                        ->whereHas('sender', function ($q) {
                            $q->where('role', '!=', 'super_admin');
                        });
                },
            ])
            ->orderByDesc('last_message_at')
            ->get();

        $conversation = $conversations->first();

        $messages = $conversation
            ? $conversation->messages()
                ->with('sender')
                ->orderBy('created_at')
                ->get()
            : collect();

        return view('admin.chat.index', compact(
            'conversations',
            'conversation',
            'messages'
        ));
    }

    /**
     * Super Admin opens tenant conversation page
     */
    public function adminConversation(Conversation $conversation)
    {
        abort_unless(Auth::user()->isSuperAdmin(), 403);

        // All tenant conversations for left sidebar
        $conversations = Conversation::with('tenant')
            ->withCount([
                'messages as unread_messages_count' => function ($query) {
                    $query->whereNull('read_at')
                        ->whereHas('sender', function ($query) {
                            $query->where('role', '!=', 'super_admin');
                        });
                },
            ])
            ->orderByDesc('last_message_at')
            ->get();

        // Current conversation messages
        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        // Mark tenant messages as read
        $conversation->messages()
            ->whereNull('read_at')
            ->whereHas('sender', function ($query) {
                $query->where('role', '!=', 'super_admin');
            })
            ->update([
                'read_at' => now(),
            ]);

        return view('admin.chat.conversation', compact(
            'conversation',
            'messages',
            'conversations'
        ));
    }

    /**
     * AJAX:
     * Get conversation messages for admin popup
     */
    public function adminConversationMessages(Conversation $conversation)
    {
        abort_unless(Auth::user()->isSuperAdmin(), 403);

        // Tenant ke unread messages read mark

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get()
            ->map(function ($message) {

                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name ?? 'User',
                    'is_mine' => $message->sender_id === Auth::id(),
                    'time' => $message->created_at->format('h:i A'),
                    'read_at' => $message->read_at?->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'tenant_name' => $conversation->tenant->name ?? 'Tenant',
            'messages' => $messages,
        ]);
    }

    /**
     * AJAX:
     * Super Admin sends message
     */
    public function adminSendMessage(
        Request $request,
        Conversation $conversation
    ) {
        $user = Auth::user();

        abort_unless($user->isSuperAdmin(), 403);

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        try {
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'message' => $validated['message'],
            ]);

            $conversation->update([
                'last_message_at' => now(),
            ]);

            $message->load('sender');

            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name ?? 'Super Admin',
                    'is_mine' => true,
                    'time' => $message->created_at->format('h:i A'),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send message
     *
     * Tenant + Admin dono use kar sakte hain.
     */
    public function send(
        Request $request,
        Conversation $conversation
    ) {
        $user = Auth::user();

        // Check if AJAX request
        if ($request->wantsJson()) {
            $validated = $request->validate([
                'message' => 'required|string|max:5000',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Tenant Security
            |--------------------------------------------------------------------------
            */

            if (! $user->isSuperAdmin()) {

                abort_unless(
                    $user->tenant_id === $conversation->tenant_id,
                    403
                );
            }

            try {
                $message = Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $user->id,
                    'message' => $validated['message'],
                ]);

                $conversation->update([
                    'last_message_at' => now(),
                ]);

                $message->load('sender');

                return response()->json([
                    'success' => true,
                    'message' => [
                        'id' => $message->id,
                        'message' => $message->message,
                        'sender_id' => $message->sender_id,
                        'sender_name' => $message->sender->name ?? 'User',
                        'is_mine' => true,
                        'time' => $message->created_at->format('h:i A'),
                    ],
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send message: '.$e->getMessage(),
                ], 500);
            }
        }

        // Non-AJAX request (fallback)
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        if (! $user->isSuperAdmin()) {

            abort_unless(
                $user->tenant_id === $conversation->tenant_id,
                403
            );
        }

        try {
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'message' => $validated['message'],
            ]);

            $conversation->update([
                'last_message_at' => now(),
            ]);

            return back()->with(
                'success',
                'Message sent successfully.'
            );
        } catch (\Exception $e) {
            return back()->with(
                'error',
                'Failed to send message: '.$e->getMessage()
            );
        }
    }

    public function markAsRead(Conversation $conversation)
    {
        $user = Auth::user();

        if (! $user->isSuperAdmin()) {
            abort_unless(
                $user->tenant_id === $conversation->tenant_id,
                403
            );

            // Tenant sirf Super Admin ke messages read kare
            $conversation->messages()
                ->whereNull('read_at')
                ->whereHas('sender', function ($query) {
                    $query->where('role', 'super_admin');
                })
                ->update([
                    'read_at' => now(),
                ]);
        } else {

            // Super Admin sirf tenant ke messages read kare
            $conversation->messages()
                ->whereNull('read_at')
                ->where('sender_id', '!=', $user->id)
                ->update([
                    'read_at' => now(),
                ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function unread(Conversation $conversation)
    {
        $user = Auth::user();

        abort_unless(
            ! $user->isSuperAdmin() &&
            $user->tenant_id === $conversation->tenant_id,
            403
        );

        $count = $conversation->messages()
            ->whereNull('read_at')
            ->whereHas('sender', function ($query) {
                $query->where('role', 'super_admin');
            })
            ->count();

        return response()->json([
            'count' => $count,
        ]);
    }

    public function tenantNotifications()
    {
        $user = Auth::user();

        abort_unless(! $user->isSuperAdmin() && $user->tenant_id, 403);

        $conversation = Conversation::where('tenant_id', $user->tenant_id)
            ->first();

        if (! $conversation) {
            return response()->json([
                'count' => 0,
                'messages' => [],
            ]);
        }

        $messages = $conversation->messages()
            ->with('sender')
            ->whereNull('read_at')
            ->whereHas('sender', function ($query) {
                $query->where('role', 'super_admin');
            })
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'count' => $messages->count(),
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_name' => $message->sender->name ?? 'Super Admin',
                    'time' => $message->created_at->diffForHumans(),
                    'url' => route('tenant.chat'), // Direct link to chat page
                ];
            }),
        ]);
    }

    /**
     * Super Admin notifications
     */
    public function adminNotifications()
    {
        $messages = Message::query()
            ->whereNull('read_at')
            ->whereHas('sender', function ($query) {
                $query->where('role', '!=', 'super_admin');
            })
            ->with([
                'sender',
                'conversation.tenant',
            ])
            ->latest()
            ->get();

        return response()->json([
            'count' => $messages->count(),

            'messages' => $messages->map(function ($message) {

                return [
                    'id' => $message->id,

                    'conversation_id' => $message->conversation_id,

                    'tenant_name' => $message->conversation?->tenant?->name ?? 'Tenant',

                    'message' => $message->message,

                    'sender_name' => $message->sender?->name ?? 'Tenant User',

                    'time' => $message->created_at?->format('h:i A'),
                ];

            }),
        ]);
    }
}
