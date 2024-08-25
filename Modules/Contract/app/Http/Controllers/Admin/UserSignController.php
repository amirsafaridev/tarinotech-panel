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
use Modules\Contract\app\Http\Requests\Admin\UserSignable\UpdateRequest;
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

    const INDEX_TITLE = 'درخواست های امضاء کارفرما';

    const EDIT_TITLE = 'درخواست های امضاء کارفرما - ویرایش';

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

            return $this->successUpdateResponse(route('admin.contract.sign.user.edit', $userSignable->id));
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
        return route('admin.contract.sign.user.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()->setName('id')->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()->setName('target.project.domain')->setAs('پروژه')
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
            $signables = UserSignable::query()
                ->with(['target.project.user', 'user'])
                ->whereHas('target.project.user');

            return DataTables::eloquent($signables)
                ->editColumn('status', function ($signable) {
                    return Helper::renderUserSignableStatus($signable->status);
                })
                ->editColumn('created_at', function ($signable) {
                    return $signable->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (UserSignable $signable) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.contract.sign.user.edit', $signable->id), trans('panel.action.edit'));

                })
                ->rawColumns(['action', 'status'])
                ->make();

        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
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
