<?php

namespace Modules\Log\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'لاگ - لیست';

    const SHOW_TITLE = 'لاگ - نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;
        $sortItems = [
            'id-desc' => 'شناسه (نزولی)',
            'id-asc' => 'شناسه (صعودی)',
        ];

        $canSort = $this->allowSort(array_keys($sortItems), request('sort'));

        $userFilter = request('user');
        $idFilter = request('id');
        $logNameFilter = request('log_name');
        $eventFilter = request('event');

        $logs = Activity::query()
            ->with(['causer' => fn (MorphTo $query) => $query->withTrashed()])
            ->when($userFilter, function (Builder $query) use ($userFilter) {
                $query->whereHas('causer', function (Builder $query) use ($userFilter) {
                    $userSearch = '%'.$userFilter.'%';
                    $query->where('first_name', 'like', $userSearch)
                        ->orWhere('last_name', 'like', $userSearch)
                        ->orWhere('email', 'like', $userSearch)
                        ->orWhere('mobile', 'like', $userSearch);
                });
            })
            ->when(is_numeric($idFilter), fn (Builder $query) => $query->where('id', $idFilter))
            ->when($logNameFilter, fn (Builder $query) => $query->where('log_name', $logNameFilter))
            ->when($eventFilter, fn (Builder $query) => $query->where('event', $eventFilter))
            ->when($canSort, function (Builder $query) use ($canSort) {
                return $query->orderBy($canSort[0], $canSort[1]);
            }, function (Builder $query) {
                return $query->orderByDesc('id');
            })
            ->paginate(10)
            ->withQueryString()
            ->setPath(route('admin.log.index'));

        return view('log::admin.index', compact('title', 'logs', 'sortItems'));
    }

    public function show(Activity $activity)
    {
        $title = self::SHOW_TITLE;

        return view('log::admin.show', compact('title', 'activity'));
    }
}
