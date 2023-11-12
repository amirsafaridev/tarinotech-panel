<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProjectBase extends Model
{
    use HasFactory,Filterable;
    use LogsActivity;

    protected $fillable = ['title'];

    public function types(): HasMany
    {
        return $this->hasMany(ProjectType::class, 'project_base_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_BASE)
            ->logAll();
    }
}
