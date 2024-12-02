<?php

namespace Modules\Contract\app\Http\Controllers\Admin;

use App\Enums\General\DropdownItemColor;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Foundation\ValueObjects\Datatable\Dropdown;
use App\Foundation\ValueObjects\Datatable\DropdownItem;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatableTrait;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Contract\app\Http\Requests\Admin\UserSignable\UpdateRequest;
use Modules\Contract\app\Models\SignableAttachment;
use Modules\Contract\app\Models\UserSignable;
use Modules\Contract\app\Traits\HandlesSignableUpdateTrait;
use Modules\Project\app\Models\ProjectAds;
use Modules\Project\app\Models\ProjectSeo;
use Modules\Project\app\Models\ProjectWeb;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class UserSignController extends Controller
{
    use HandlesSignableUpdateTrait;
    use HasDatatableTrait;
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'درخواست های امضاء کارفرما';

    const EDIT_TITLE = 'درخواست های امضاء کارفرما';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('contract::admin.user_signable.index', compact('title', 'routeData', 'dataTable'));

    }

    public function edit(UserSignable $userSignable)
    {

        $relationshipsToLoad = $this->determineRelationshipsToLoad($userSignable);
        $userSignable->load($relationshipsToLoad);

        $title = self::EDIT_TITLE.' - '.$userSignable->target?->project->title;

        return view('contract::admin.user_signable.edit', compact('title', 'userSignable'));
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateRequest $request, UserSignable $userSignable)
    {
        try {
            DB::beginTransaction();

            $oldStatus = $userSignable->status;

            $itemData = $this->prepareItemData($request);
            $userSignable->update($itemData);

            $this->updateAttachments($request->input('attachments'), $userSignable);

            $this->handleSignableUpdate($userSignable, $oldStatus, $request->all());

            DB::commit();

            return $this->successUpdateResponse(route('admin.contract.sign.user.edit', $userSignable->id));
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function show(UserSignable $userSignable)
    {
        $relationshipsToLoad = $this->determineRelationshipsToLoad($userSignable);
        $userSignable->load($relationshipsToLoad);
        $title = self::EDIT_TITLE.' - '.$userSignable->target?->project->title;

        return view('contract::admin.user_signable.show', compact('title', 'userSignable'));
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
                ColumnOption::new()->setName('target_id')->setAs('شناسه پروژه')
                    ->setSearchable(true)
                    ->setSortable(true)
            )
            ->addColumn(
                ColumnOption::new()->setName('target.project.domain')->setAs('پروژه')
                    ->setSearchable(false)
                    ->setSortable(false)
            )
            ->addColumn(
                ColumnOption::new()->setName('target.project.admin.last_name')->setAs('کارشناس')
                    ->setSearchable(false)
                    ->setSortable(false)
            )
            ->addColumn(
                ColumnOption::new()->setName('user.first_name')->setAs('نام')
                    ->setSearchable(false)
                    ->setSortable(false)
            )
            ->addColumn(
                ColumnOption::new()->setName('user.last_name')->setAs('نام خانوادگی')
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
                ->with(['target.project.admin', 'user']);

            return DataTables::eloquent($signables)
                ->editColumn('status', function ($signable) {
                    return Helper::renderUserSignableStatus($signable->status);
                })
                ->editColumn('created_at', function ($signable) {
                    return $signable->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (UserSignable $signable) {

                    return (new Dropdown())
                        ->add(
                            (new DropdownItem())
                                ->setTargetBlank(true)
                                ->setTitle(trans('panel.action.edit'))
                                ->setLink(route('admin.contract.sign.user.edit', $signable->id))
                        )
                        ->add(
                            (new DropdownItem())
                                ->setTargetBlank(true)
                                ->setTitle(trans('panel.action.show'))
                                ->setLink(route('admin.contract.sign.user.show', $signable->id))
                        )
                        ->setButtonColor(DropdownItemColor::Success())
                        ->render();

                })
                ->rawColumns(['action', 'status'])
                ->make();

        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    private function determineRelationshipsToLoad($userSignable): array
    {
        $relationshipsToLoad = ['attachments', 'files'];

        if (in_array($userSignable->target_type, [ProjectWeb::class, ProjectSeo::class])) {
            $relationshipsToLoad[] = 'target.project';
            $relationshipsToLoad[] = 'target.package';
        } elseif ($userSignable->target_type === ProjectAds::class) {
            $relationshipsToLoad[] = 'target.project';
        }

        return $relationshipsToLoad;
    }
}
