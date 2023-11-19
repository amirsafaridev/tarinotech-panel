<?php

namespace Modules\Auth\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OtpCode extends Model
{
    use HasFactory;
    use HasUlids;
    use LogsActivity;

    protected $primaryKey = 'id';

    protected $fillable = [
        'identify',
        'expired_at',
        'ip',
        'agent',
        'code',
    ];

    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::OTP_CODE)
            ->logAll();
    }
}
