<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class TestEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $status;
    public $time;

    public function __construct($message = "Jaringan Bermasalah!")
    {
        $this->message = $message;
        $this->status = "error";
        $this->time = now()->format('H:i:s');
    }

    public function broadcastOn(): Channel
    {
        return new Channel('test-channel');
    }

    public function broadcastAs(): string
    {
        return 'TestEvent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'status'  => $this->status,
            'time'    => $this->time,
        ];
    }
}
