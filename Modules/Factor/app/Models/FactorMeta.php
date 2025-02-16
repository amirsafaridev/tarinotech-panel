<?php

namespace Modules\Factor\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Log\app\Enums\LogNames;
use Modules\Project\app\Models\ProjectType;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FactorMeta extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'customer_fullname',
        'customer_mobile',
        'project_title',
        'factor_id',
        'type_id',
    ];

    public function factor(): BelongsTo
    {
        return $this->belongsTo(Factor::class, 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class, 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::FACTOR_META)
            ->logAll();
    }
}
