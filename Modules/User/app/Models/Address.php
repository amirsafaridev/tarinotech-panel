<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;

class Address extends Model
{
    use HasFactory;

    protected array $logAttributes = ['*'];

    protected $fillable = [
        'address',
        'postal_code',
        'user_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::ADDRESS)
            ->logAll();
    }
}
