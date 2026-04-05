<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class IssueNotification extends Notification
{
    use Queueable;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via(object $notifiable): array
    {
        return ['database']; 
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->data;
    }

    public function toArray(object $notifiable): array
    {
        return $this->data;
    }
}