<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFacilityRenewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'project_renewal_id',
        'price_type',
        'price_value',
        'work_cycle',
        'work_cycle_value',
        'financial_cycle',
        'financial_cycle_value',
        'days',
        'description',
        'calculated_price',
    ];

    protected $casts = [
        'renewal_at' => 'date',
        'added_at' => 'date',
        'work_cycle_value' => 'date',
    ];

    public function projectRenewal(): BelongsTo
    {
        return $this->belongsTo(ProjectRenewal::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}
