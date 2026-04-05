<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\IncidentDetected;
use App\Services\RuleEngine\IncidentRuleService;

class MonitorNetwork extends Command
{
    protected $signature = 'network:monitor';
    protected $description = 'Cek status jaringan, buat tiket, dan kirim notifikasi otomatis';

    public function handle(IncidentRuleService $ruleService): int
    {
        $this->info('--- Memulai Monitoring Jaringan ---');
        $monitoringData = [
            [
                'customer_id' => 1,
                'status' => 'DOWN',      
                'latency' => 999,        
                'packet_loss' => 100,
            ],
            [
                'customer_id' => 2, 
                'status' => 'UP',
                'latency' => 500,
                'packet_loss' => 40,
            ],
        ];

        $createdCount = 0;

        foreach ($monitoringData as $data) {
            $this->line("Mengevaluasi data untuk Customer ID: " . ($data['customer_id'] ?? 'Unknown'));

            $result = $ruleService->evaluate($data);

            if ($result) {
                $ticket = Ticket::create([
                    'title'       => "AUTO-DETECT: " . $result['issue_type'],
                    'description' => "Gangguan terdeteksi otomatis pada pelanggan ID " . $data['customer_id'] . ". Detail: " . $result['description'],
                    'status'      => 'critical',
                    'priority'    => strtolower($result['priority']), 
                    'location'    => "Pelanggan ID " . $data['customer_id'], 
                    'reported_by' => 'SYSTEM_ALGORITHM',
                    'reported_at' => now(),
                    'is_active'   => true,
                ]);

                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new IncidentDetected([
                        'type'      => 'network_issue',
                        'title'     => 'Gangguan Terdeteksi!',
                        'message'   => "Pelanggan {$data['customer_id']} mengalami {$result['issue_type']}",
                        'ticket_id' => $ticket->id,
                        'location'  => "Customer Jalur " . $data['customer_id']
                    ]));
                }

                $this->warn(">> Tiket & Notifikasi dibuat: {$result['issue_type']}");
                $createdCount++;
            } else {
                $this->info(">> Status Normal.");
            }
        }

        $this->info("--- Monitoring Selesai. {$createdCount} tiket otomatis dibuat. ---");

        return Command::SUCCESS;
    }
}