<?php

namespace Modules\Admin\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\app\Models\Admin;

class BonusesDeduction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['price', 'type', 'user_id'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }
    public function reasons(): HasMany
    {
        return $this->hasMany(Reason::class);
    }
}
