<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JobCard extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    public const STATUSES = [
        'pending', 'in_progress', 'waiting_parts', 'quality_check',
        'completed', 'delivered', 'cancelled',
    ];

    protected $fillable = [
        'company_id', 'branch_id', 'vehicle_id', 'customer_id', 'bay_id',
        'assigned_mechanic_id', 'supervisor_id', 'created_by', 'status', 'priority',
        'odometer_reading', 'complaint_description', 'diagnosis',
        'estimated_completion_at', 'started_at', 'completed_at', 'delivered_at',
        'total_labor_cost', 'total_parts_cost', 'discount_amount', 'tax_amount', 'grand_total',
    ];

    protected $casts = [
        'estimated_completion_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'delivered_at' => 'datetime',
        'total_labor_cost' => 'decimal:2',
        'total_parts_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (JobCard $job) {
            $job->uuid = (string) Str::uuid();
            $job->job_number = $job->job_number ?? static::generateJobNumber($job->company_id);
        });
    }

    public static function generateJobNumber(?int $companyId): string
    {
        $year = now()->format('Y');
        $count = static::withoutCompanyScope()
            ->where('company_id', $companyId)
            ->whereYear('created_at', $year)
            ->count() + 1;

        return sprintf('JC-%s-%06d', $year, $count);
    }

    /** Recalculates totals from line items. Call after any item add/update/remove. */
    public function recalculateTotals(): void
    {
        $labor = $this->items()->where('type', 'labor')->sum('total_price');
        $parts = $this->items()->where('type', 'part')->sum('total_price');
        $subtotal = $labor + $parts;

        $this->total_labor_cost = $labor;
        $this->total_parts_cost = $parts;
        $this->grand_total = max($subtotal - $this->discount_amount + $this->tax_amount, 0);
        $this->save();
    }

    // ---- Relationships ----
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function bay(): BelongsTo
    {
        return $this->belongsTo(Bay::class);
    }

    public function assignedMechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_mechanic_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(JobCardItem::class);
    }

    public function gateLogs(): HasMany
    {
        return $this->hasMany(GateLog::class);
    }
}
