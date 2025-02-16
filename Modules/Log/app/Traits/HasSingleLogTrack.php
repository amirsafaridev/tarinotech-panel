<?php

namespace Modules\Log\app\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;

trait HasSingleLogTrack
{
    public function trackChanges(string $propertyToTrack, Model $model, ?int $subjectId): Collection
    {
        try {
            return Activity::query()
                ->with('causer')
                ->when($subjectId, function ($query) use ($subjectId) {
                    $query->where('subject_id', $subjectId);
                })
                ->where('subject_type', get_class($model))
                ->where('event', 'updated')
                ->where(
                    "properties->old->{$propertyToTrack}",
                    '!=',
                    "properties->attributes->{$propertyToTrack}"
                )
                ->latest()
                ->get()
                ->map(function ($item) use ($propertyToTrack) {
                    return [
                        'id' => $item->id,
                        'log_name' => $item->log_name,
                        'event' => $item->event,
                        'created_at' => $item->created_at,
                        'old_property' => $item->properties['old'][$propertyToTrack] ?? null,
                        'new_property' => $item->properties['attributes'][$propertyToTrack] ?? null,
                        'causer' => $item->causer,
                        'causer_type' => $item->causer_type,
                    ];
                });

        } catch (Exception $e) {
            report($e);

            return collect();
        }
    }
}
