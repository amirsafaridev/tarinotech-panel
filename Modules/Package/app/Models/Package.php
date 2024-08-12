<?php

namespace Modules\Package\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Log\app\Enums\LogNames;
use Modules\Project\app\Models\ProjectType;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Package extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'title',
        'type_id',
        'contract_attachment',
        'min_contract_price',
        'seo_keywords_count',
        'seo_agreement_duration',
        'seo_amount_content',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(PackagePrice::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class, 'type_id');
    }

    public function finalPrice(): HasOne
    {
        return $this->hasOne(PackagePrice::class)->orderByDesc('id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PACKAGE)
            ->logAll();
    }
}
