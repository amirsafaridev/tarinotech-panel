<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\Database\Role\PermissionName;
use App\Filters\Admin\Admin\AdminJoinedFilter;
use App\Http\Controllers\Controller;
use App\Service\PdfService;
use App\Traits\HasJsonCommonResponseTrait;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Project\app\Exports\Admin\Report\ProjectDatatableExport;
use Modules\Project\app\Filters\IsSignFilter;
use Modules\Project\app\Filters\IsUserSignFilter;
use Modules\Project\app\Filters\Project\DateFilter;
use Modules\Project\app\Filters\Project\SearchFilter;
use Modules\Project\app\Filters\Project\SortFilter;
use Modules\Project\app\Filters\StatusFilter;
use Modules\Project\app\Filters\TypeFilter;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectWeb;
use Mpdf\MpdfException;

class ProjectController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'پروژه ها';

    const SHOW_TITLE = 'نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $selectedColumns = collect([
            'projects.id',
            'projects.title',
            'projects.admin_id',
            'projects.price',
            'projects.domain',
            'projects.created_at',
            'projects.is_signed',
            'projects.is_signed_user',
            'admins.first_name as admin_first_name',
            'admins.last_name as admin_last_name',
            'users.first_name as user_first_name',
            'users.last_name as user_last_name',
            'project_types.title as project_types_title',
            'project_bases.title as project_bases_title',
            'project_statuses.title as project_statuses_title',
        ]);

        $hasPricePermission = hasAdminPermission(PermissionName::PROJECT_PRICE_SHOW);
        if ($hasPricePermission) {
            $selectedColumns->add('projects.price');
        }

        $projects = Project::query()
            ->select($selectedColumns->toArray())
            ->join('admins', 'projects.admin_id', '=', 'admins.id')
            ->join('users', 'projects.user_id', '=', 'users.id')
            ->join('project_types', 'projects.type_id', '=', 'project_types.id')
            ->join('project_bases', 'projects.base_id', '=', 'project_bases.id')
            ->join('project_statuses', 'projects.status_id', '=', 'project_statuses.id')
            ->filter([
                SearchFilter::class,
                AdminJoinedFilter::class,
                TypeFilter::class,
                StatusFilter::class,
                IsSignFilter::class,
                IsUserSignFilter::class,
                DateFilter::class,
                SortFilter::class,
            ]);

        if (request('export')) {
            return $this->export($projects->get());
        }

        $projects = $projects->paginate();

        return view('project::admin.index', compact('title', 'projects', 'hasPricePermission'));
    }

    private function export($factors)
    {
        try {
            $fileName = 'Project-'.Carbon::now()->format('Y-m-d').'.xlsx';

            return Excel::download(new ProjectDatatableExport(collect($factors)), $fileName);
        } catch (Exception $exception) {
            report($exception);

            return back()->with('danger', 'خطا در هنگام صادر کردن اطلاعات');
        }
    }

    public function manage(Project $project)
    {

        $title = self::SHOW_TITLE.' - '.$project->title;

        $this->getProjectWithRelation($project);

        if ($project->target_type === ProjectWeb::class) {
            $project->load('target.options');
        }

        return view('project::admin.show', compact('title', 'project'));
    }

    public function print(Project $project, PdfService $pdfService)
    {
        try {
            $this->getProjectWithRelation($project);

            $this->setupPdfService($pdfService);

            $view = view('project::admin.pdf.show', compact('project'))->render();

            $pdfService->writeHtml($view);

            return $pdfService->output(str($project->title)->replace(' ', ''), 'I');
        } catch (Exception $e) {
            report($e);

            return $e->getMessage();
        }
    }

    /**
     * @throws MpdfException
     */
    private function setupPdfService(PdfService $service)
    {
        $service->setFont('DejaVuSans', 'B', 14);
        $service->setMargins(0, 0, 10);
    }

    protected function getProjectWithRelation(Project $project): void
    {
        $project->load([
            'admin',
            'user',
            'status',
            'type',
            'businessDomain',
            'factors.admin',
            'target.signable.files',
            'target.userSignable.attachments',
            'target.userSignable.files',
        ]);
    }
}
