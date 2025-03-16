<?php

namespace Modules\Survey\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SurveyAnswer extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'response_id',
        'question_id',
        'answer_text',
        'rating_value',
        'created_at',
        'updated_at',
    ];

    public function response(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'response_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(SurveyAnswerOption::class, 'answer_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::SURVEY_ANSWER)
            ->logAll();
    }
}
