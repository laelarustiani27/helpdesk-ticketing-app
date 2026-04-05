<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class IncidentDetected extends Notification
{
    use Queueable;

    private $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toArray($notifiable)
    {
        return [
            'type'      => $this->details['type'] ?? 'network_issue',
            'title'     => $this->details['title'],
            'message'   => $this->details['message'],
            'location'  => $this->details['location'] ?? 'N/A',
            'ticket_id' => $this->details['ticket_id'] ?? null,
        ];
    }
}