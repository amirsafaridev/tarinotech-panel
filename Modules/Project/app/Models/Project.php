<?php

namespace Modules\Project\app\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\app\Models\PresenterProject;
use Modules\Factor\app\Models\Factor;
use Modules\Log\app\Enums\LogNames;
use Modules\Support\app\Models\Chat;
use Modules\User\app\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use Filterable;
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'domain',
        'admin_id',
        'user_id',
        'price',
        'status_id',
        'base_id',
        'type_id',
        'target_type',
        'target_id',
        'agreement_at',
        'deadline_at',
        'renewal_at',
        'note',
        'business_domain_id',
        'business_domain',
    ];

    protected $casts = [
        'deadline_at' => 'date',
        'agreement_at' => 'date',
        'renewal_at' => 'date',
    ];

    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    public function base(): BelongsTo
    {
        return $this->belongsTo(ProjectBase::class, 'base_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class, 'type_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ProjectStatus::class, 'status_id');
    }

    public function businessDomain(): BelongsTo
    {
        return $this->belongsTo(BusinessDomain::class, 'business_domain_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'project_id');
    }

    public function presenter(): HasMany
    {
        return $this->hasMany(PresenterProject::class, 'project_id');
    }

    public function factors(): HasMany
    {
        return $this->hasMany(Factor::class, 'project_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT)
            ->logAll();
    }
}
