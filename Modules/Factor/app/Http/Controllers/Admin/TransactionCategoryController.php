<?php

namespace Modules\Factor\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Modules\Factor\app\Http\Requests\Admin\TransactionCategory\StoreRequest;
use Modules\Factor\app\Http\Requests\Admin\TransactionCategory\UpdateRequest;
use Modules\Factor\app\Models\TransactionCategory;
use Yajra\DataTables\Facades\DataTables;

class TransactionCategoryController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'انواع واریزی';

    const CREATE_TITLE = 'انواع واریزی - ایجاد';

    const EDIT_TITLE = 'انواع واریزی - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('factor::admin.category.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('factor::admin.category.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $item = $this->prepareItemData($request);
            TransactionCategory::create($item);

            return $this->successResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(TransactionCategory $transactionCategory)
    {
        $title = self::EDIT_TITLE;

        return view('factor::admin.category.edit', compact('title', 'transactionCategory'));
    }

    public function update(UpdateRequest $request, TransactionCategory $transactionCategory)
    {
        try {
            $item = $this->prepareItemData($request);
            $transactionCategory->update($item);

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    public function destroy(TransactionCategory $transactionCategory)
    {
        try {
            $transactionCategory->delete();

            return $this->successDestroyBack(route('admin.factor.category.index'));

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['project_type_id'] = $request->input('project_type_id');

        return $item;
    }

    public function getDataRoute(): string
    {
        return route('admin.factor.category.data');
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
            $categories = TransactionCategory::query();

            return DataTables::eloquent($categories)
                ->editColumn('created_at', function (TransactionCategory $category) {
                    return $category->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (TransactionCategory $category) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.factor.category.edit', $category->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
