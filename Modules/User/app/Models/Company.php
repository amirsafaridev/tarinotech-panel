<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Company extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'name',
        'identify',
        'register_id',
        'type',
        'user_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::COMPANY)
            ->logAll();
    }
}
