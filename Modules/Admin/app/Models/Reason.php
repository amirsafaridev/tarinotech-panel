<?php

namespace Modules\Admin\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Database\factories\ReasonFactory;

class Reason extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['description', 'bonuses_deduction_id'];
    public function bonusesDeduction(): BelongsTo
    {
        return $this->belongsTo(BonusesDeduction::class, 'bonuses_deduction_id');
    }
}
