<?php

namespace Modules\User\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatableTrait;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Modules\User\app\Http\Requests\Admin\Communication\StoreRequest;
use Modules\User\app\Http\Requests\Admin\Communication\UpdateRequest;
use Modules\User\app\Models\Communication;
use Yajra\DataTables\Facades\DataTables;

class CommunicationController extends Controller
{
    use HasDatatableTrait;
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'راه های ارتباطی';

    const CREATE_TITLE = 'راه های ارتباطی - ایجاد';

    const EDIT_TITLE = 'راه های ارتباطی - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('user::admin.communication.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('user::admin.communication.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            Communication::query()
                ->create($this->prepareItemData($request));

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Communication $communication)
    {
        $title = self::EDIT_TITLE;

        return view('user::admin.communication.edit', compact('title', 'communication'));
    }

    public function update(UpdateRequest $request, Communication $communication)
    {
        try {
            $communication->update($this->prepareItemData($request));

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Communication $communication)
    {
        try {
            $communication->delete();

            return $this->successDestroyBack(route('admin.communication.index'));

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['title'] = $request->input('title');

        return $item;
    }

    public function getDataRoute(): string
    {
        return route('admin.communication.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()->setName('id')->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()->setName('title')->setAs('عنوان')
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
            $communications = Communication::query();

            return DataTables::eloquent($communications)
                ->editColumn('created_at', function ($communication) {
                    return $communication->created_at->toJalali()->format(formatJalaliDate());
                })
                ->addColumn('action', function ($communication) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.communication.edit', $communication->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
