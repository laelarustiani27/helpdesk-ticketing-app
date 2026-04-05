<?php

namespace App\Helpers;

use App\Models\User;
use App\Notifications\IssueNotification;

class NotificationHelper
{
    public static function notifyTeknisiReport($ticket, $reporter)
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new IssueNotification([
                'type'      => 'teknisi',
                'title'     => "{$reporter->nama_lengkap} — Laporan Masalah Baru",
                'message'   => "Teknisi melaporkan: '{$ticket->title}' di {$ticket->location}.",
                'location'  => $ticket->location,
                'ticket_id' => $ticket->id,
                'priority'  => $ticket->priority,
            ]));
        }
    }

    public static function notifyUnassignedIssue($ticket)
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new IssueNotification([
                'type'      => 'sistem',
                'title'     => 'Error Jaringan Terdeteksi Otomatis',
                'message'   => "'{$ticket->title}' prioritas {$ticket->priority} belum ditugaskan ke teknisi.",
                'location'  => $ticket->location,
                'ticket_id' => $ticket->id,
                'priority'  => $ticket->priority,
            ]));
        }
    }

    public static function notifyCustomerReport($ticket, $customer)
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new IssueNotification([
                'type'      => 'pelanggan',
                'title'     => "{$customer->nama_lengkap} — Laporan Masalah Baru",
                'message'   => "Pelanggan melaporkan: '{$ticket->title}' di {$ticket->location}.",
                'location'  => $ticket->location,
                'ticket_id' => $ticket->id,
                'priority'  => $ticket->priority,
            ]));
        }
    }

    public static function notifyIssueAssigned($ticket, $teknisi)
    {
        $teknisi->notify(new IssueNotification([
            'type'      => 'sistem',
            'title'     => 'Tugas Baru Ditugaskan ke Anda',
            'message'   => "Anda mendapat tugas baru: '{$ticket->title}' prioritas {$ticket->priority} di {$ticket->location}.",
            'location'  => $ticket->location,
            'ticket_id' => $ticket->id,
            'priority'  => $ticket->priority,
        ]));
    }
}