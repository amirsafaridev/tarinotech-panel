<?php

namespace Modules\Survey\app\Models;

use App\Traits\Filterable;
use App\Traits\HasUniqueIdentify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\app\Models\Admin;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Survey extends Model
{
    use Filterable;
    use HasFactory;
    use HasUniqueIdentify;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'admin_id',
        'requires_auth',
        'auth_guard',
        'is_active',
        'has_meta',
        'start_date',
        'end_date',
        'access_token',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'requires_auth' => 'boolean',
        'is_active' => 'boolean',
        'has_meta' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('order');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function metas(): HasMany
    {
        return $this->hasMany(SurveyMeta::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::SURVEY)
            ->logAll();
    }

    public function identifiable(): string
    {
        return 'access_token';
    }
}
