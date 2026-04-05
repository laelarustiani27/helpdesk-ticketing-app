<?php

namespace App\Console\Commands;

use App\Mail\DeviceDownMail;
use App\Models\Device;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckDevices extends Command
{
    protected $signature = 'devices:check';
    protected $description = 'Cek status perangkat jaringan via ping';

    public function handle()
    {
        $devices = Device::all();

        foreach ($devices as $device) {
            $isUp = $this->pingDevice($device->ip_address);

            if (!$isUp && $device->status === 'up') {
                $device->update([
                    'status'       => 'down',
                    'last_down_at' => now(),
                ]);

                Ticket::create([
                    'nama_tempat' => $device->nama,
                    'alamat'      => $device->lokasi ?? '-',
                    'status'      => 'open',
                    'priority'    => 'critical',
                    'title'       => 'Perangkat Down: ' . $device->nama,
                    'description' => 'Perangkat ' . $device->nama . ' dengan IP ' . $device->ip_address . ' tidak dapat dijangkau.',
                    'location'    => $device->lokasi ?? '-',
                    'reported_at' => now(),
                ]);

                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new DeviceDownMail($device));
                }

                $this->info("DOWN: {$device->nama} ({$device->ip_address})");

            } elseif ($isUp && $device->status === 'down') {
                $device->update([
                    'status'     => 'up',
                    'last_up_at' => now(),
                ]);

                $this->info("UP: {$device->nama} ({$device->ip_address})");
            }
        }

        $this->info('Pengecekan selesai.');
    }

    private function pingDevice(string $ip): bool
    {
        $os      = strtoupper(substr(PHP_OS, 0, 3));
        $command = $os === 'WIN'
            ? "ping -n 1 -w 1000 {$ip}"
            : "ping -c 1 -W 1 {$ip}";

        exec($command, $output, $result);

        return $result === 0;
    }
}