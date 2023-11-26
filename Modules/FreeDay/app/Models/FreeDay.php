<?php

namespace Modules\FreeDay\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FreeDay extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $casts = [
        'free_at' => 'date',
    ];

    protected $fillable = [
        'title',
        'free_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::FREE_DAY)
            ->logAll();
    }
}
