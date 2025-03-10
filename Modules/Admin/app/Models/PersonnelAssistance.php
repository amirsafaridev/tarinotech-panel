<?php

namespace Modules\Admin\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\app\Models\Admin;

class PersonnelAssistance extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['price', 'description','user_id'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    // protected static function newFactory(): PersonnelAssistanceFactory
    // {
    //     //return PersonnelAssistanceFactory::new();
    // }
}