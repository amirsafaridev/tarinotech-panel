<?php

namespace Modules\Project\app\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProjectOption extends Model
{
    use Filterable;
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'title',
        'base_id',
    ];

    public function base(): BelongsTo
    {
        return $this->belongsTo(ProjectBase::class, 'base_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_WEB)
            ->logAll();
    }
}
