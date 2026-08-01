<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Bay;
use App\Models\JobCard;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JobCardService
{
    /** Valid forward transitions. Prevents e.g. jumping straight from pending to delivered. */
    private array $allowedTransitions = [
        'pending' => ['in_progress', 'cancelled'],
        'in_progress' => ['waiting_parts', 'quality_check', 'cancelled'],
        'waiting_parts' => ['in_progress', 'cancelled'],
        'quality_check' => ['completed', 'in_progress'],
        'completed' => ['delivered'],
        'delivered' => [],
        'cancelled' => [],
    ];

    public function create(array $data, int $userId): JobCard
    {
        return DB::transaction(function () use ($data, $userId) {
            $job = JobCard::create([
                ...$data,
                'created_by' => $userId,
                'status' => 'pending',
            ]);

            if (!empty($data['bay_id'])) {
                Bay::whereKey($data['bay_id'])->update(['status' => 'occupied']);
            }

            foreach ($data['items'] ?? [] as $item) {
                $job->items()->create($item);
            }

            return $job->fresh(['items', 'vehicle', 'customer', 'bay']);
        });
    }

    /**
     * @throws ValidationException
     */
    public function transitionStatus(JobCard $job, string $newStatus, int $userId): JobCard
    {
        $allowed = $this->allowedTransitions[$job->status] ?? [];

        if (!in_array($newStatus, $allowed, true)) {
            throw ValidationException::withMessages([
                'status' => ["Cannot move job from '{$job->status}' to '{$newStatus}'."],
            ]);
        }

        $timestampField = match ($newStatus) {
            'in_progress' => 'started_at',
            'completed' => 'completed_at',
            'delivered' => 'delivered_at',
            default => null,
        };

        $updates = ['status' => $newStatus];
        if ($timestampField) {
            $updates[$timestampField] = now();
        }

        $job->update($updates);

        if (in_array($newStatus, ['completed', 'delivered', 'cancelled'], true) && $job->bay_id) {
            Bay::whereKey($job->bay_id)->update(['status' => 'available']);
        }

        AuditLog::create([
            'company_id' => $job->company_id,
            'user_id' => $userId,
            'action' => 'job_status_changed',
            'auditable_type' => JobCard::class,
            'auditable_id' => $job->id,
            'new_values' => ['status' => $newStatus],
        ]);

        return $job->fresh(['items', 'vehicle', 'customer', 'bay']);
    }

    public function addItem(JobCard $job, array $itemData): JobCard
    {
        $job->items()->create($itemData);

        return $job->fresh(['items']);
    }
}
