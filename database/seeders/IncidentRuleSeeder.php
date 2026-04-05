<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidentRuleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('incident_rules')->insert([
            [
                'field'      => 'status',
                'operator'   => '=',
                'value'      => 'DOWN',
                'issue_type' => 'SYSTEM_DOWN',
                'priority'   => 'CRITICAL',
                'is_active'  => 1,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'field'      => 'latency',
                'operator'   => '>',
                'value'      => '200',
                'issue_type' => 'NETWORK_SLOW',
                'priority'   => 'HIGH',
                'is_active'  => 1,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'field'      => 'packet_loss',
                'operator'   => '>',
                'value'      => '5',
                'issue_type' => 'PACKET_LOSS',
                'priority'   => 'MEDIUM',
                'is_active'  => 1,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
        ]);
    }
}