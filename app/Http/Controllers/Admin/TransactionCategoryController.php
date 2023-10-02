<?php

namespace App\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TransactionCategory\StoreRequest;
use App\Http\Requests\Admin\TransactionCategory\UpdateRequest;
use App\Models\TransactionCategory;
use DB;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionCategoryController extends Controller
{
    public function index()
    {
        $title = 'انواع واریزی';
        $routeData = route('admin.transaction-category.data');
        $selects = ['id', 'title', 'created_at'];

        return view('admin.transaction_category.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {
        try {
            $transactionCategories = TransactionCategory::query();

            return DataTables::of($transactionCategories)
                ->editColumn('created_at', function (TransactionCategory $transactionCategory) {
                    return $transactionCategory->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (TransactionCategory $transactionCategory) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.transaction-category.edit', $transactionCategory->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = 'انواع واریزی - نوع جدید';
        $routeStore = route('admin.transaction-category.store');

        return view('admin.transaction_category.create', compact('title', 'routeStore'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            TransactionCategory::create($item);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_store'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => trans('panel.error_store'),
            ], 500);
        }
    }

    public function edit(TransactionCategory $transactionCategory)
    {
        $title = 'انواع واریزی - ویرایش';
        $routeUpdate = route('admin.transaction-category.update', $transactionCategory->id);
        $routeDestroy = route('admin.transaction-category.destroy', $transactionCategory->id);

        return view('admin.transaction_category.edit', compact('title', 'routeUpdate', 'routeDestroy', 'transactionCategory'));
    }

    public function update(UpdateRequest $request, TransactionCategory $transactionCategory)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $transactionCategory->update($item);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_update'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => trans('panel.error_update'),
            ], 500);
        }
    }

    public function destroy(TransactionCategory $transactionCategory)
    {
        try {
            $transactionCategory->delete();

            return redirect(route('admin.transaction-category.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.transaction-category.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['title'] = $request->get('title');
        $item['project_type_id'] = $request->get('project_type_id');

        return $item;
    }
}
