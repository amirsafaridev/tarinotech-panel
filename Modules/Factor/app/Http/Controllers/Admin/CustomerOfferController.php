<?php

namespace Modules\Factor\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Filters\Admin\Admin\AdminFilter;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Foundation\ValueObjects\Datatable\ExternalFilter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatableTrait;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Filters\Factor\PriceFilter;
use Modules\Factor\app\Filters\Factor\ProjectFilter;
use Modules\Factor\app\Models\Factor;
use Yajra\DataTables\Facades\DataTables;

class CustomerOfferController extends Controller
{
    use HasDatatableTrait;
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'فاکتور ها ( آفر مشتریان)';

    const EDIT_TITLE = 'فاکتور ها ( آفر مشتریان) - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('factor::admin.customer_offer.index', compact('title', 'routeData', 'dataTable'));
    }

    public function edit(Factor $factor)
    {
        $this->isCustomerOfferFactor($factor);

        $title = self::EDIT_TITLE;

        return view('factor::admin.customer_offer.edit', compact('title', 'factor'));
    }

    public function update(Request $request, Factor $factor)
    {
        try {

            $this->isCustomerOfferFactor($factor);

            $updatedAttributes = $this->prepareItemData($request);

            $factor->update($updatedAttributes);

            return $this->successUpdateResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $factorData['is_confirm'] = $request->has('is_confirm');

        return $factorData;
    }

    public function getDataRoute(): string
    {
        return route('admin.factor.customer-offer.data');
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
                ColumnOption::new()->setName('is_confirm')->setAs('وضیعت تایید')
            )
            ->addColumn(
                ColumnOption::new()->setName('paid_at')->setAs('تاریخ پرداخت')
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
                    'is_confirm',
                    'paid_at',
                    'created_at',
                ])
                ->filter([
                    PriceFilter::class,
                    ProjectFilter::class,
                    AdminFilter::class,
                ])
                ->has('project')
                ->where('status', FactorStatus::CustomerOffer)
                ->with([
                    'admin' => function ($query) {
                        $query->select('admins.id', 'admins.first_name', 'admins.last_name');
                    },
                    'project' => function ($query) {
                        $query->select('projects.id', 'projects.title', 'projects.domain');
                    },
                ]);

            return DataTables::eloquent($factors)
                ->editColumn('is_confirm', function (Factor $factor) {
                    return Helper::renderBoolean($factor->is_confirm);
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
                ->editColumn('paid_at', function (Factor $factor) {
                    return $factor->paid_at?->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (Factor $factor) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.factor.customer-offer.edit', $factor->id), trans('panel.action.edit'));
                })
                ->rawColumns(['action', 'is_confirm'])
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    protected function isCustomerOfferFactor(Factor $factor): void
    {
        if ($factor->status !== FactorStatus::CustomerOffer) {
            abort(404);
        }
    }
}
