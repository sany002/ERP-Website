<?php

namespace App\Services;

use App\Models\GateLog;
use App\Models\Vehicle;

class GateLogService
{
    public function logEntry(array $data, int $operatorId): GateLog
    {
        $vehicleId = $data['vehicle_id'] ?? null;

        // Try to auto-attach the vehicle's currently open job card, if any.
        $jobCardId = $data['job_card_id'] ?? null;
        if (!$jobCardId && $vehicleId) {
            $jobCardId = Vehicle::find($vehicleId)
                ?->jobCards()
                ->whereNotIn('status', ['delivered', 'cancelled'])
                ->latest()
                ->value('id');
        }

        return GateLog::create([
            ...$data,
            'job_card_id' => $jobCardId,
            'gate_operator_id' => $operatorId,
            'logged_at' => $data['logged_at'] ?? now(),
        ]);
    }
}
