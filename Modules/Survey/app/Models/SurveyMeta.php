<?php

namespace Modules\Survey\app\Models;

use App\Traits\HasUniqueIdentify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SurveyMeta extends Model
{
    use HasFactory;
    use HasUniqueIdentify;

    protected $fillable = [
        'survey_id',
        'surveyable_type',
        'surveyable_id',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function surveyable(): MorphTo
    {
        return $this->morphTo();
    }

    public function identifiable(): string
    {
        return 'access_token';
    }
}
