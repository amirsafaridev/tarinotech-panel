<?php

namespace Modules\Admin\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\app\Models\Admin;
use Carbon\Carbon;

class PersonnelReport extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'date',
        'start_time',
        'end_time',
        'description',
        'status',
        'rules_accepted',
        'is_emergency'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
        'rules_accepted' => 'boolean',
        'is_emergency' => 'boolean'
    ];

    /**
     * Get the user that owns the personnel report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    /**
     * Get the total hours of leave
     */
    public function getTotalHoursAttribute(): int
    {
        if ($this->type === 'daily') {
            return 9; // هر روز کامل معادل 9 ساعت
        }

        return Carbon::parse($this->end_time)->diffInHours(Carbon::parse($this->start_time));
    }

    /**
     * Get the status text
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'در انتظار تایید',
            'approved' => 'تایید شده',
            'rejected' => 'رد شده',
            default => 'نامشخص'
        };
    }

    /**
     * Get the type text
     */
    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'daily' => 'روزانه',
            'hourly' => 'ساعتی',
            default => 'نامشخص'
        };
    }

    /**
     * Scope a query to only include pending reports.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved reports.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected reports.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include emergency reports.
     */
    public function scopeEmergency($query)
    {
        return $query->where('is_emergency', true);
    }

    /**
     * Scope a query to only include reports for current month.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->where(function($q) {
            $q->whereBetween('start_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
              ->orWhereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        });
    }
}
