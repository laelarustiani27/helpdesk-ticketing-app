<?php

namespace App\Mail;

use App\Models\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeviceDownMail extends Mailable
{
    use Queueable, SerializesModels;

    public Device $device;

    public function __construct(Device $device)
    {
        $this->device = $device;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Perangkat Down: ' . $this->device->nama,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.device-down',
            with: [
                'device' => $this->device,
            ]
        );
    }
}