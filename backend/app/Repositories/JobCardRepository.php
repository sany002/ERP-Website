<?php

namespace App\Repositories;

use App\Models\JobCard;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class JobCardRepository
{
    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = JobCard::query()
            ->with(['vehicle', 'customer', 'bay', 'assignedMechanic:id,name', 'items']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($mechanicId = $request->query('mechanic_id')) {
            $query->where('assigned_mechanic_id', $mechanicId);
        }

        if ($bayId = $request->query('bay_id')) {
            $query->where('bay_id', $bayId);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('job_number', 'like', "%{$search}%")
                    ->orWhereHas('vehicle', fn ($v) => $v->where('registration_number', 'like', "%{$search}%"))
                    ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        return $query->latest()->paginate($request->integer('per_page', 20));
    }

    public function find(int $id): JobCard
    {
        return JobCard::with(['vehicle', 'customer', 'bay', 'assignedMechanic', 'supervisor', 'items', 'gateLogs'])
            ->findOrFail($id);
    }
}
