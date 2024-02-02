<?php

namespace Modules\User\app\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Auth\app\Models\Login;
use Modules\Log\app\Enums\LogNames;
use Modules\Project\app\Models\Project;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use Filterable;
    use HasApiTokens;
    use HasFactory;
    use LogsActivity;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'en_first_name',
        'en_last_name',
        'father_name',
        'national_id',
        'document_id',
        'email',
        'avatar',
        'national_photo',
        'dob',
        'person_type',
        'official_bill',
        'mobile',
        'verify_at',
        'is_block',
        'user_type',
        'remember_token',

        'knowledge_way_id',
        'knowledge_way',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verify_at' => 'datetime',
        'password' => 'hashed',
        'dob' => 'datetime',
    ];

    protected $appends = ['fullname'];

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function irnic(): HasOne
    {
        return $this->hasOne(Irnic::class);
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function knowledgeWay(): HasOne
    {
        return $this->hasOne(KnowledgeWay::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function accessProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'presenter_project');
    }

    public function logins(): MorphMany
    {
        return $this->morphMany(Login::class, 'user');
    }

    public function latestLogin(): MorphOne
    {
        return $this->morphOne(Login::class, 'user')->latest('login_at');
    }

    public function phones(): HasMany
    {
        return $this->hasMany(UseCellphone::class, 'user_id');
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn () => $this->first_name.' '.$this->last_name
        );
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value !== '' ? $value : null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::USER)
            ->logExcept(['password'])
            ->logAll();
    }
}
