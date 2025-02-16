<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class KnowledgeWay extends Model
{
    use HasFactory;
    use LogsActivity;

    protected array $logAttributes = ['*'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'knowledge_way_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::KNOW_LEDGE_WAY)
            ->logAll();
    }
}
