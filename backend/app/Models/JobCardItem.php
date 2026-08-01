<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobCardItem extends Model
{
    protected $fillable = [
        'job_card_id', 'type', 'inventory_item_id', 'mechanic_id',
        'name', 'description', 'quantity', 'unit_price', 'total_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (JobCardItem $item) {
            $item->total_price = round($item->quantity * $item->unit_price, 2);
        });

        static::saved(fn (JobCardItem $item) => $item->jobCard->recalculateTotals());
        static::deleted(fn (JobCardItem $item) => $item->jobCard->recalculateTotals());
    }

    public function jobCard(): BelongsTo
    {
        return $this->belongsTo(JobCard::class);
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }
}
