<?php

namespace App\Http\Controllers\Admin\Project;

use App\Filters\Admin\Project\BaseIdFilter;
use App\Filters\Admin\Project\DomainFilter;
use App\Filters\Admin\Project\SortFilter;
use App\Filters\Admin\Project\StatusFilter;
use App\Filters\Admin\Share\IDFilter;
use App\Filters\Admin\User\UserSearchFilter;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectAds;
use App\Models\ProjectSeo;
use App\Models\ProjectWeb;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Modules\Admin\app\Models\Admin;

class ProjectController extends Controller
{
    public function index()
    {
        $title = 'پروژه ها';

        $projects = Project::query()
            ->with(['status', 'target', 'user', 'admin', 'base'])
            ->whereHasMorph('target', [ProjectAds::class, ProjectWeb::class, ProjectSeo::class])
            ->whereHas('user', function (Builder $q) {
                $q->filter([
                    UserSearchFilter::class,
                ]);
            })
            ->filter([
                IDFilter::class,
                DomainFilter::class,
                BaseIdFilter::class,
                StatusFilter::class,
                SortFilter::class,
            ])
            ->paginate(12);

        $sortItems = [
            'id-desc' => 'شناسه (نزولی)',
            'id-asc' => 'شناسه (صعودی)',
            'price-desc' => 'قیمت (نزولی)',
            'price-asc' => 'قیمت (صعودی)',
        ];

        return view('admin.project.index', compact('title', 'projects', 'sortItems'));
    }

    public function destroy(Admin $admin)
    {
        try {
            $admin->delete();

            return redirect(route('admin.admin.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return redirect(route('admin.admin.index'))->with('danger', trans('panel.error_delete'));
        }
    }
}
