<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Database\Eloquent\Builder;
use Modules\Factor\app\Models\Factor;
use Modules\Project\app\Models\ProjectRenewal;

class ProjectRenewalController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'لیست تمدیدها';

    const SHOW_TITLE = 'لیست تمدیدها - نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $renewals = ProjectRenewal::query()
            ->select(
                'project_renewals.id',
                'project_renewals.status',
                'project_renewals.package_calculated_price',
                'project_renewals.project_id',
                'project_renewals.created_at',
                'projects.id as project_id',
                'projects.title as project_title',
                'projects.domain as project_domain',
                'projects.agreement_at as project_agreement_at',
                'projects.renewal_at as project_renewal_at',
                'admins.first_name as admin_first_name',
                'admins.last_name as admin_last_name'
            )
            ->has('project')
            ->join('projects', 'project_id', '=', 'projects.id')
            ->join('admins', 'projects.admin_id', '=', 'admins.id')

            // Filter by search term
            ->when(request('search'), function (Builder $query): void {
                $search = escapeLike(request('search'));
                $query->where(function (Builder $q) use ($search): void {
                    $q->where('projects.title', 'like', '%'.$search.'%')
                        ->orWhere('projects.domain', 'like', '%'.$search.'%')
                        ->orWhere('projects.id', 'like', '%'.$search.'%');
                });
            })

            // Filter by admin name
            ->when(request('admin'), function (Builder $query): void {
                $admin = escapeLike(request('admin'));
                $query->where(function (Builder $q) use ($admin): void {
                    $q->where('admins.first_name', 'like', '%'.$admin.'%')
                        ->orWhere('admins.last_name', 'like', '%'.$admin.'%');
                });
            })

            // Filter by status
            ->when(request('status'), function (Builder $query): void {
                $query->where('status', request('status'));
            })

            // Sort results
            ->when(
                request('sort') && preg_match('/^(created_at)\|(asc|desc)$/', request('sort')),
                function (Builder $query): void {
                    $query->orderBy(...explode('|', request('sort')));
                },
                function (Builder $query): void {
                    $query->orderBy('created_at', 'desc');
                }
            )
            ->paginate()
            ->withQueryString();

        return view('project::admin.renewal.index', compact('title', 'renewals'));
    }

    public function show(int $projectRenewalId)
    {
        $projectRenewal = ProjectRenewal::findWithRelations($projectRenewalId);

        $factors = Factor::query()
            ->where('project_id', $projectRenewal->project_id)
            ->where('is_automate', true)
            ->whereHas('items', function ($query) {
                $query->where('transaction_category_id', 16);
            })
            ->latest()
            ->get();

        $title = self::SHOW_TITLE;

        return view('project::admin.renewal.show', compact('title', 'projectRenewal', 'factors'));
    }
}
