<?php

namespace Modules\Login\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Auth\app\Models\Login;

class LoginController extends Controller
{
    const INDEX_TITLE = 'لاگین ها - لیست';

    const SHOW_TITLE = 'لاگین ها - نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;
        $sortItems = [
            'id-desc' => 'شناسه (نزولی)',
            'id-asc' => 'شناسه (صعودی)',

            'login_at-desc' => 'تاریخ ورود (نزولی)',
            'login_at-asc' => 'تاریخ ورود (صعودی)',
        ];

        $canSort = $this->allowSort(array_keys($sortItems), request('sort'));

        $userFilter = request('user');

        $logins = Login::query()
            ->with(['user' => fn (MorphTo $query) => $query->withTrashed()])
            ->when($userFilter, function (Builder $query) use ($userFilter) {
                $query->whereHas('user', function (Builder $query) use ($userFilter) {
                    $userSearch = '%'.$userFilter.'%';
                    $query->where('first_name', 'like', $userSearch)
                        ->orWhere('last_name', 'like', $userSearch)
                        ->orWhere('email', 'like', $userSearch)
                        ->orWhere('mobile', 'like', $userSearch);
                });
            })
            ->when($canSort, function (Builder $query) use ($canSort) {
                return $query->orderBy($canSort[0], $canSort[1]);
            }, function (Builder $query) {
                return $query->orderByDesc('id');
            })
            ->paginate(10)
            ->withQueryString();

        return view('login::admin.index', compact('title', 'sortItems', 'logins'));
    }

    public function show(Login $login)
    {
        $title = self::SHOW_TITLE;

        return view('login::admin.show', compact('title', 'login'));
    }
}
