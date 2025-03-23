<?php

namespace Modules\Project\app\Models;

use App\Traits\Filterable;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\app\Models\PresenterProject;
use Modules\Factor\app\Models\Factor;
use Modules\Log\app\Enums\LogNames;
use Modules\Support\app\Models\Chat;
use Modules\Survey\app\Models\SurveyMeta;
use Modules\User\app\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use CascadeSoftDeletes;
    use Filterable;
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected array $cascadeDeletes = ['factors', 'chats'];

    protected $fillable = [
        'title',
        'domain',
        'admin_id',
        'user_id',
        'price',
        'tax_rate',
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
        'contract_attachment',
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

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'project_facilities')
            ->withTimestamps()
            ->withPivot(['renewal_at', 'id']);
        //->with('facility');
    }

    public function factors(): HasMany
    {
        return $this->hasMany(Factor::class, 'project_id');
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(ProjectRenewal::class, 'project_id');
    }

    public function survey(): MorphOne
    {
        return $this->morphOne(SurveyMeta::class, 'surveyable');
    }

    public static function findSeoTarget($projectId): ?self
    {
        return self::query()
            ->whereHasMorph('target', [ProjectSeo::class])
            ->with('target')
            ->findOrFail($projectId);
    }

    public static function findWebTarget($projectId): ?self
    {
        return self::query()
            ->whereHasMorph('target', [ProjectWeb::class])
            ->with(['target', 'facilities'])
            ->findOrFail($projectId);
    }

    public static function findAdsTarget($projectId): ?self
    {
        return self::query()
            ->whereHasMorph('target', [ProjectAds::class])
            ->with('target')
            ->findOrFail($projectId);
    }

    public function syncFacilitiesPreserveRenewal(array $facilities): array
    {
        $currentFacilities = $this->facilities()
            ->get()
            ->pluck('pivot.renewal_at', 'id')
            ->map(fn ($date) => ['renewal_at' => $date])
            ->toArray();

        $syncData = collect($facilities)->mapWithKeys(function ($facilityId) use ($currentFacilities) {
            return [
                $facilityId => $currentFacilities[$facilityId] ?? ['renewal_at' => $currentFacilities[$facilityId]['renewal_at'] ?? now()],
            ];
        })->toArray();

        return $this->facilities()->sync($syncData);
    }

    public function getRenewalStatus(): array
    {
        $conditions = [
            'target' => [
                'condition' => $this->target instanceof ProjectWeb,
                'message' => 'این نوع پروژه قابل تمدید نیست',
            ],
            'package' => [
                'condition' => $this->target && $this->target->package,
                'message' => 'پکیج برای پروژه تعریف نشده است',
            ],
            'user' => [
                'condition' => $this->user,
                'message' => 'کاربر برای پروژه تعریف نشده است',
            ],
            'agreement' => [
                'condition' => $this->agreement_at,
                'message' => 'تاریخ قرارداد ثبت نشده است',
            ],
            'renewal' => [
                'condition' => $this->renewal_at,
                'message' => 'تاریخ تمدید تعیین نشده است',
            ],
        ];

        // Check conditions and return failure message if any condition fails
        foreach ($conditions as $key => $value) {
            if (! $value['condition']) {
                return [
                    'canRenew' => false,
                    'message' => $value['message'],
                    'daysUntilRenewal' => null,
                    'renewalDate' => null,
                ];
            }
        }

        $daysUntilRenewal = now()->diffInDays($this->renewal_at, false);
        $canRenew = $this->renewal_at <= now();

        return [
            'canRenew' => $canRenew,
            'message' => null,
            'daysUntilRenewal' => $daysUntilRenewal,
            'renewalDate' => $this->renewal_at,
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT)
            ->logAll();
    }
}
