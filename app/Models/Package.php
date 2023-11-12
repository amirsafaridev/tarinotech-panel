<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Package extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'title',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(PackagePrice::class);
    }

    public function finalPrice(): HasOne
    {
        return $this->hasOne(PackagePrice::class)->orderByDesc('id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PACKAGE)
            ->logAll();
    }
}
