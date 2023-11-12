<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PackagePrice extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'package_id',
        'price',
        'start_at',
        'end_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PACKAGE_PRICE)
            ->logAll();
    }
}
