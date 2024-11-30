<?php

namespace Modules\Contract\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatableTrait;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Contract\app\Enums\SignableStatus;
use Modules\Contract\app\Http\Requests\Admin\Signable\UpdateRequest;
use Modules\Contract\app\Models\Signable;
use Modules\Contract\app\Models\UserSignable;
use Modules\Contract\app\Traits\HandlesSignableUpdateTrait;
use Modules\Project\app\Models\ProjectSeo;
use Modules\Project\app\Models\ProjectWeb;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class SignController extends Controller
{
    use HandlesSignableUpdateTrait;
    use HasDatatableTrait;
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'درخواست های امضاء';

    const EDIT_TITLE = 'درخواست های امضاء';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('contract::admin.signable.index', compact('title', 'routeData', 'dataTable'));

    }

    public function edit(Signable $signable)
    {
        $signable->load(['files', 'target.project']);

        $title = self::EDIT_TITLE.' - '.$signable->target?->project->title;

        return view('contract::admin.signable.edit', compact('title', 'signable'));
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateRequest $request, Signable $signable)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            $oldStatus = $signable->status;
            $signable->update($item);

            if ($this->shouldCreateUserSignable($request, $signable)) {
                $this->createUserSignable($signable);
            }

            $this->handleSignableUpdate($signable, $oldStatus, $request->all());

            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Signable $signable)
    {
        try {
            $signable->delete();

            return $this->successDestroyBack(route('admin.contract.sign.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $req): array
    {
        $signableData['status'] = $req->input('status');
        $signableData['note'] = $req->input('note');
        $signableData['sign_at'] = now();

        return $signableData;
    }

    public function getDataRoute(): string
    {
        return route('admin.contract.sign.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()->setName('id')->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()->setName('target.project.domain')->setAs('پروژه')
                    ->setSearchable(false)
                    ->setSortable(false)
            )
            ->addColumn(
                ColumnOption::new()->setName('make_admin.last_name')->setAs('کارشناس')
                    ->setSearchable(false)
                    ->setSortable(false)
            )
            ->addColumn(
                ColumnOption::new()->setName('target.project.user.first_name')->setAs('نام')
                    ->setSearchable(false)
                    ->setSortable(false)
            )
            ->addColumn(
                ColumnOption::new()->setName('target.project.user.last_name')->setAs('نام خانوادگی')
                    ->setSearchable(false)
                    ->setSortable(false)
            )
            ->addColumn(
                ColumnOption::new()->setName('status')->setAs('وضعیت')
            )
            ->addColumn(
                ColumnOption::new()->setName('sign_at')->setAs('تاریخ امضاء')
            )
            ->addColumn(
                ColumnOption::new()->setName('created_at')->setAs('ایجاد')
            )
            ->addColumn(
                ColumnOption::new()->setName('action')
                    ->setAs('عملیات')
                    ->removeAction()
            )
            ->render();
    }

    public function data()
    {
        try {
            $signables = Signable::query()
                ->with(['target.project.user', 'makeAdmin']);

            return DataTables::eloquent($signables)
                ->editColumn('status', function ($signable) {
                    return Helper::renderSignableStatus($signable->status);
                })
                ->editColumn('sign_at', function ($signable) {
                    return $signable->sign_at ? $signable->sign_at->toJalali()->format(formatJalaliDateTime()) : '-';
                })
                ->editColumn('created_at', function ($signable) {
                    return $signable->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (Signable $signable) {
                    $actions = Helper::btnMaker(BtnType::Info, makeRouteContractPreview($signable->target_type, $signable->target_id), trans('panel.action.printContract'));
                    $actions .= Helper::btnMaker(BtnType::Warning, route('admin.contract.sign.edit', $signable->id), trans('panel.action.edit'));

                    return $actions;
                })
                ->rawColumns(['action', 'status'])
                ->make();

        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    private function shouldCreateUserSignable(UpdateRequest $request, Signable $signable): bool
    {
        return $request->input('status') === SignableStatus::Signed &&
            ($signable->target_type === ProjectWeb::class || $signable->target_type === ProjectSeo::class);
    }

    private function createUserSignable(Signable $signable): void
    {
        UserSignable::query()->firstOrCreate(
            [
                'target_type' => $signable->target_type,
                'target_id' => $signable->target_id,
                'user_id' => $signable->target->project->user_id,
            ]
        );
    }
}
