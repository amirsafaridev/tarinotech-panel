<?php

namespace Modules\Support\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SampleMessage extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'title',
        'message',
        'type',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::SAMPLE_MESSAGE)
            ->logAll();
    }
}
