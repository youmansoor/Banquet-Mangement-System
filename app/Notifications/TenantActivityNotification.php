<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TenantActivityNotification extends Notification
{
    use Queueable;

    protected string $type;

    protected array $data;

    public function __construct(
        string $type,
        array $data = []
    ) {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Notification channels
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Database notification data
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => $this->type,

            'title' => $this->data['title']
                ?? 'Tenant Activity',

            'message' => $this->data['message']
                ?? '',

            'booking_id' => $this->data['booking_id']
                ?? null,

            'tenant_id' => $this->data['tenant_id']
                ?? null,

            'tenant_name' => $this->data['tenant_name']
                ?? 'Tenant',

            'customer_name' => $this->data['customer_name']
                ?? null,

            'amount' => $this->data['amount']
                ?? null,

            'url' => $this->data['url']
                ?? null,
        ];
    }
}
