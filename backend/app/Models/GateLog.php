<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GateLog extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id', 'branch_id', 'vehicle_id', 'registration_number', 'job_card_id',
        'direction', 'gate_operator_id', 'odometer_reading', 'driver_name',
        'remarks', 'photo', 'logged_at',
    ];

    protected $casts = ['logged_at' => 'datetime'];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function jobCard(): BelongsTo
    {
        return $this->belongsTo(JobCard::class);
    }

    public function gateOperator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gate_operator_id');
    }
}
