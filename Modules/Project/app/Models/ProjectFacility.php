<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Admin\app\Models\Admin;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProjectFacility extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $casts = [
        'renewal_at' => 'date',
    ];

    protected $fillable = [
        'project_id',
        'facility_id',
        'user_id',
        'renewal_at',
    ];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
   public function user(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_FACILITY)
            ->logAll();
    }
}
