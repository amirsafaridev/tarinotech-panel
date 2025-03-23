<?php

namespace Modules\Survey\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SurveyQuestionOption extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'survey_question_id',
        'option_text',
        'order',
        'color_code',
        'created_at',
        'updated_at',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    public function answerOptions(): HasMany
    {
        return $this->hasMany(SurveyAnswerOption::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::QUESTION_OPTION)
            ->logAll();
    }
}
