<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Contract\app\Models\Signable;
use Modules\Contract\app\Models\UserSignable;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProjectSeo extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'project_seo';

    protected $fillable = [
        'field_activity',
        'host',
        'agreement_duration',
        'amount_content',
        'keywords_count',
        'keywords',
        'price_monthly',
        'due_date_payments',
        'designed_by',
    ];

    protected $casts = [
        'host' => 'json',
    ];

    public function project(): MorphOne
    {
        return $this->morphOne(Project::class, 'target');
    }

    public function signable(): MorphOne
    {
        return $this->morphOne(Signable::class, 'target');
    }

    public function userSignable(): MorphOne
    {
        return $this->morphOne(UserSignable::class, 'target');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_SEO)
            ->logAll();
    }
}
