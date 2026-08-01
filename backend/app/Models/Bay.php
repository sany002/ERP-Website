<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bay extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'branch_id', 'name', 'code', 'type', 'status', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function jobCards(): HasMany
    {
        return $this->hasMany(JobCard::class);
    }
}
