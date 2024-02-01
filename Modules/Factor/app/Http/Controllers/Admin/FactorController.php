<?php

namespace Modules\Factor\app\Http\Controllers\Admin;

use App\Domin\Jobs\FactorItemCreateJob;
use App\Domin\Jobs\FactorItemUpdateJob;
use App\Enums\Database\Factor\FactorStatus;
use App\Enums\General\BtnType;
use App\Filters\Admin\Admin\AdminFilter;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Foundation\ValueObjects\Datatable\ExternalFilter;
use App\Foundation\ValueObjects\Requests\FactorItemValues;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Factor\app\Filters\Factor\PriceFilter;
use Modules\Factor\app\Filters\Factor\ProjectFilter;
use Modules\Factor\app\Filters\Factor\StatusFilter;
use Modules\Factor\app\Http\Requests\Admin\Factor\StoreRequest;
use Modules\Factor\app\Http\Requests\Admin\Factor\UpdateRequest;
use Modules\Factor\app\Models\Factor;
use Modules\Factor\app\Models\FactorItem;
use Modules\Factor\app\Models\TransactionCategory;
use Modules\Project\app\Models\Project;
use Modules\User\app\Enums\PersonType;
use Yajra\DataTables\Facades\DataTables;

class FactorController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'فاکتور ها';

    const CREATE_TITLE = 'فاکتور ها - ایجاد';

    const EDIT_TITLE = 'فاکتور ها - ویرایش';

    const SHOW_TITLE = 'فاکتور ها - نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('factor::admin.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('factor::admin.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {

            DB::beginTransaction();
            $factor = Factor::query()->create($this->prepareItemData($request));

            foreach ($request->input('item') as $item) {
                resolve(FactorItemCreateJob::class)->handle(
                    $this->setItemValues($factor, $item)
                );

            }

            if ($request->input('custom_customer') == 'yes') {
                $factor->meta()->create($this->prepareMeta($request));
            }

            DB::commit();

            $this->updateFinalPrice($factor);

            return $this->successResponse();
        } catch (Exception $exception) {

            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    private function updateFinalPrice(Factor $factor)
    {
        $itemPrice = FactorItem::query()
            ->where('factor_id', $factor->id)
            ->sum('final_price');

        $factor->update([
            'final_price' => $itemPrice,
        ]);
    }

    public function edit(Factor $factor)
    {
        $factor->load(['items', 'project', 'meta']);

        $title = self::EDIT_TITLE;
        $categories = TransactionCategory::query()->get();

        return view('factor::admin.edit', compact('title', 'factor', 'categories'));
    }

    public function update(UpdateRequest $request, Factor $factor)
    {
        try {

            DB::beginTransaction();
            $factor->load('items');
            $updatedItemIds = [];
            foreach ($request->input('item') as $item) {
                $factorItemValues = $this->setItemValues($factor, $item);
                $action = $item['action'];
                if ($action === 'store') {
                    resolve(FactorItemCreateJob::class)->handle($factorItemValues);
                } else {
                    $updatedItemIds[] = $item['id'];
                    resolve(FactorItemUpdateJob::class)
                        ->handle($factorItemValues, $item['id']);
                }
            }

            $currentItemIds = $factor->items->pluck('id')->toArray();
            $deletedItemIds = array_diff($currentItemIds, $updatedItemIds);
            if (count($deletedItemIds)) {
                FactorItem::query()
                    ->whereIn('id', $deletedItemIds)
                    ->delete();
            }

            $updatedAttributes = $this->prepareItemData($request);
            $updatedAttributes['status'] = $request->input('status');
            $factor->update($updatedAttributes);

            if ($request->input('custom_customer') == 'yes' && ! $factor->project_id) {
                $factor->meta()->update($this->prepareMeta($request));
            }

            DB::commit();

            $this->updateFinalPrice($factor);

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function show(Factor $factor)
    {
        $title = self::SHOW_TITLE;

        $factor->load(['items.category', 'project.user', 'admin', 'meta.type']);

        return view('factor::admin.show', compact('title', 'factor'));
    }

    public function destroy(Factor $factor)
    {
        try {
            $factor->delete();

            return $this->successDestroyBack(route('admin.factor.index'));

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionBack($exception);

        }
    }

    protected function prepareItemData(Request $request): array
    {
        $factorData['title'] = $request->input('title');

        $factorData['expired_at'] = Helper::toGregorian($request->input('expired_at'));
        $factorData['status'] = FactorStatus::Pending;

        $factorData['gateway_data'] = [];
        $factorData['admin_id'] = auth()->id();

        $factorData['is_official'] = false;

        $customCustomer = $request->input('custom_customer');
        $projectId = $request->input('project_id');

        if ($customCustomer == 'no') {
            $factorData['project_id'] = $projectId;

            $project = Project::query()
                ->with('user')
                ->findOrFail($factorData['project_id']);

            $user = $project->user;
            if ($user->person_type === PersonType::Legal || $user->official_bill) {
                $factorData['is_official'] = true;
            }
        }

        return $factorData;
    }

    protected function prepareMeta(Request $request): array
    {
        $factorMetaData['customer_fullname'] = $request->input('customer_fullname');
        $factorMetaData['customer_mobile'] = $request->input('customer_mobile');
        $factorMetaData['project_title'] = $request->input('project_title');
        $factorMetaData['type_id'] = $request->input('type_id');

        return $factorMetaData;
    }

    private function setItemValues(Factor $factor, array $item): FactorItemValues
    {
        return resolve(FactorItemValues::class)
            ->setFactorId($factor->id)
            ->setTitle($item['title'])
            ->setTransactionCategoryId($item['transaction_category_id'])
            ->setPrice($item['price'])
            ->setDiscount($item['discount']);
    }

    public function getDataRoute(): string
    {
        return route('admin.factor.data');
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
                ColumnOption::new()
                    ->setName('admin.fullname')->setAs('کارشناس')
                    ->setSearchable(false)
                    ->setSortable(false)
            )
            ->addColumn(
                ColumnOption::new()->setName('project.title')
                    ->setAs('پروژه')
                    ->setSortable(false)
            )
            ->addColumn(
                ColumnOption::new()->setName('final_price')->setAs('مبلغ (ریال)')
            )
            ->addColumn(
                ColumnOption::new()->setName('status')->setAs('وضعیت')
            )
            ->addColumn(
                ColumnOption::new()->setName('expired_at')->setAs('مهلت پرداخت')
            )
            ->addColumn(
                ColumnOption::new()->setName('created_at')->setAs('ایجاد')
            )
            ->addColumn(
                ColumnOption::new()->setName('action')
                    ->setAs('عملیات')
                    ->removeAction()
            )
            ->addExternalFilter(ExternalFilter::new()->setKey('project'))
            ->addExternalFilter(ExternalFilter::new()->setKey('admin'))
            ->addExternalFilter(ExternalFilter::new()->setKey('price_from')->isPrice())
            ->addExternalFilter(ExternalFilter::new()->setKey('price_to')->isPrice())
            ->addExternalFilter(ExternalFilter::new()->setKey('status'))
            ->render();
    }

    public function data()
    {
        try {
            $factors = Factor::query()
                ->select([
                    'id',
                    'title',
                    'admin_id',
                    'project_id',
                    'final_price',
                    'status',
                    'expired_at',
                    'created_at',
                ])
                ->filter([
                    PriceFilter::class,
                    StatusFilter::class,
                    ProjectFilter::class,
                    AdminFilter::class,
                ])
                ->with([
                    'admin' => function ($query) {
                        $query->select('admins.id', 'admins.first_name', 'admins.last_name');
                    },
                    'project' => function ($query) {
                        $query->select('projects.id', 'projects.title', 'projects.domain');
                    },
                ]);

            return DataTables::eloquent($factors)
                ->editColumn('status', function (Factor $factor) {
                    return factorStatusRender($factor->status);
                })
                ->editColumn('final_price', function (Factor $factor) {
                    return number_format($factor->final_price);
                })
                ->editColumn('project.title', function (Factor $factor) {
                    return $factor->project_id ? $factor->project->title : 'پروژه ندارد';
                })
                ->editColumn('created_at', function (Factor $factor) {
                    return $factor->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->editColumn('expired_at', function (Factor $factor) {
                    return $factor->expired_at?->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (Factor $factor) {
                    $action = Helper::btnMaker(BtnType::Warning, route('admin.factor.edit', $factor->id), trans('panel.action.edit'));
                    $action .= Helper::btnMaker(BtnType::Info, route('admin.factor.show', $factor->id), trans('panel.action.show'));

                    return $action;
                })
                ->rawColumns(['action', 'status'])
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
