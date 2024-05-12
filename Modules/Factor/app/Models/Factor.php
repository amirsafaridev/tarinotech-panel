<?php

namespace Modules\Factor\app\Models;

use App\Traits\Filterable;
use App\Traits\HasUniqueIdentify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\app\Models\Admin;
use Modules\Log\app\Enums\LogNames;
use Modules\Project\app\Models\Project;
use Modules\User\app\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Factor extends Model
{
    use Filterable;
    use HasFactory;
    use HasUniqueIdentify;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'admin_id',
        'identify',
        'final_price',
        'transaction_id',
        'project_id',
        'status',
        'is_official',
        'expired_at',
        'gateway_data',
        'gateway',
        'paid_at',
    ];

    protected $casts = [
        'gateway_data' => 'json',
        'expired_at' => 'date',
        'is_official' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(FactorItem::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Project::class, 'id', 'id', 'project_id', 'user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function meta(): HasOne
    {
        return $this->hasOne(FactorMeta::class);
    }

    public function identifiable(): string
    {
        $this->isInt = true;

        return 'identify';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::FACTOR)
            ->logAll();
    }
}
