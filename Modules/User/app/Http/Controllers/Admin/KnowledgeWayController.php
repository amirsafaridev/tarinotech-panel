<?php

namespace Modules\User\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Modules\User\app\Http\Requests\Admin\KnowledgeWay\StoreRequest;
use Modules\User\app\Http\Requests\Admin\KnowledgeWay\UpdateRequest;
use Modules\User\app\Models\KnowledgeWay;
use Yajra\DataTables\Facades\DataTables;

class KnowledgeWayController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'راه های آشنایی';

    const CREATE_TITLE = 'راه های آشنایی - ایجاد';

    const EDIT_TITLE = 'راه های آشنایی - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('user::admin.knowledge_way.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('user::admin.knowledge_way.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            KnowledgeWay::query()
                ->create($this->prepareItemData($request));

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(KnowledgeWay $knowledgeWay)
    {
        $title = self::EDIT_TITLE;

        return view('user::admin.knowledge_way.edit', compact('title', 'knowledgeWay'));
    }

    public function update(UpdateRequest $request, KnowledgeWay $knowledgeWay)
    {
        try {
            $knowledgeWay->update($this->prepareItemData($request));

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(KnowledgeWay $knowledgeWay)
    {
        try {
            $knowledgeWay->delete();

            return $this->successDestroyBack(route('admin.knowledge-way.index'));

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
        return route('admin.knowledge-way.data');
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
            $knowledgeWays = KnowledgeWay::query();

            return DataTables::eloquent($knowledgeWays)
                ->editColumn('created_at', function ($knowledgeWay) {
                    return $knowledgeWay->created_at->toJalali()->format(formatJalaliDate());
                })
                ->addColumn('action', function ($knowledgeWay) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.knowledge-way.edit', $knowledgeWay->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
