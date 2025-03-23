<?php

namespace Modules\Survey\app\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SurveyResponse extends Model
{
    use Filterable;
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'survey_id',
        'survey_meta_id',
        'respondent_type',
        'respondent_id',
        'respondent_email',
        'respondent_name',
        'ip_address',
        'session_id',
        'started_at',
        'completed_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function respondent(): MorphTo
    {
        return $this->morphTo();
    }

    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class, 'response_id');
    }

    public function meta(): BelongsTo
    {
        return $this->belongsTo(SurveyMeta::class, 'survey_meta_id');
    }

    public function getCompletionTimeInSeconds(): ?int
    {
        if (! $this->started_at || ! $this->completed_at) {
            return null;
        }

        return $this->completed_at->diffInSeconds($this->started_at);
    }

    public function getCompletionTimeString(): string
    {
        if (! $this->started_at || ! $this->completed_at) {
            return 'نامشخص';
        }

        $seconds = $this->getCompletionTimeInSeconds();
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        return "$minutes دقیقه و $remainingSeconds ثانیه";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::SURVEY_RESPONSE)
            ->logAll();
    }
}
