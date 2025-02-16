<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UserCommunication extends Model
{
    use HasFactory;
    use LogsActivity;

    public $timestamps = false;

    protected array $logAttributes = ['*'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'communication_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::USER_COMMUNICATION)
            ->logAll();
    }
}
