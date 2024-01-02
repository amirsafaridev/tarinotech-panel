<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessDomain extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::BUSINESS_DOMAIN)
            ->logAll();
    }
}
