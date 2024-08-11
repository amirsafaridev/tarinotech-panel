<?php

namespace Modules\Contract\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Contract\app\Enums\SignableStatus;
use Modules\Contract\app\Http\Requests\Admin\UserSignable\UpdateRequest;
use Modules\Contract\app\Models\Signable;
use Modules\Contract\app\Models\SignableAttachment;
use Modules\Contract\app\Models\UserSignable;
use Modules\Project\app\Models\ProjectAds;
use Modules\Project\app\Models\ProjectSeo;
use Modules\Project\app\Models\ProjectWeb;
use Yajra\DataTables\Facades\DataTables;

class UserSignController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'درخواست های امضاء';

    const EDIT_TITLE = 'درخواست های امضاء - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('contract::admin.user_signable.index', compact('title', 'routeData', 'dataTable'));

    }

    public function edit(UserSignable $userSignable)
    {
        $title = self::EDIT_TITLE;

        $relationshipsToLoad = $this->determineRelationshipsToLoad($userSignable);
        $userSignable->load($relationshipsToLoad);

        return view('contract::admin.user_signable.edit', compact('title', 'userSignable'));
    }

    public function update(UpdateRequest $request, UserSignable $userSignable)
    {
        try {
            DB::beginTransaction();

            // Prepare the data for updating the UserSignable model
            $itemData = $this->prepareItemData($request);
            $userSignable->update($itemData);

            // Update attachments if any are provided
            $this->updateAttachments($request->input('attachments'), $userSignable);

            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    /**
     * Update attachments based on the provided IDs.
     */
    protected function updateAttachments(?array $attachmentIds, UserSignable $userSignable): void
    {
        if (empty($attachmentIds)) {
            return;
        }

        SignableAttachment::query()->whereIn('ulid', $attachmentIds)
            ->update([
                'target_type' => UserSignable::class,
                'target_id' => $userSignable->id,
                'is_used' => true,
            ]);
    }

    protected function prepareItemData(Request $req): array
    {
        $signableData['status'] = $req->input('status');
        $signableData['note'] = $req->input('note');

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
                ColumnOption::new()->setName('make_admin.fullname')->setAs('درخواست کننده')
            )
            ->addColumn(
                ColumnOption::new()->setName('target.project.domain')->setAs('پروژه')
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
                ->with(['target.project', 'makeAdmin']);

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
            $signable->target_type === ProjectWeb::class;
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

    private function determineRelationshipsToLoad($userSignable): array
    {
        $relationshipsToLoad = ['attachments'];

        if (in_array($userSignable->target_type, [ProjectWeb::class, ProjectSeo::class])) {
            $relationshipsToLoad[] = 'target.project';
            $relationshipsToLoad[] = 'target.package';
        } elseif ($userSignable->target_type === ProjectAds::class) {
            $relationshipsToLoad[] = 'target.project';
        }

        return $relationshipsToLoad;
    }
}
