<?php

namespace Modules\Survey\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SurveyAnswerOption extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'answer_id',
        'survey_question_option_id',
        'created_at',
        'updated_at',
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(SurveyAnswer::class, 'answer_id');
    }

    public function questionOption(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestionOption::class, 'survey_question_option_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::ANSWER_OPTION)
            ->logAll();
    }
}
