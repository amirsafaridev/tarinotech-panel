<?php

namespace Modules\Factor\app\Http\Controllers\Admin;

use App\Domain\Jobs\FactorItemCreateJob;
use App\Enums\General\BtnType;
use App\Filters\Admin\Admin\AdminFilter;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Foundation\ValueObjects\Datatable\ExternalFilter;
use App\Foundation\ValueObjects\Requests\FactorItemValues;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Service\Json\WebProject\DomainTransformer;
use App\Service\Json\WebProject\HostTransformer;
use App\Service\Json\WebProject\LanguageTransformer;
use App\Service\Json\WebProject\SampleTransformer;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Enums\PaymentGateway;
use Modules\Factor\app\Filters\Factor\GatewayFilter;
use Modules\Factor\app\Filters\Factor\PriceFilter;
use Modules\Factor\app\Filters\Factor\ProjectFilter;
use Modules\Factor\app\Filters\Factor\StatusFilter;
use Modules\Factor\app\Http\Requests\Admin\Factor\StoreRequest;
use Modules\Factor\app\Models\Factor;
use Modules\Factor\app\Models\FactorItem;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectWeb;
use Modules\User\app\Enums\PersonType;
use Modules\User\app\Models\User;
use Yajra\DataTables\Facades\DataTables;

class FactorController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'فاکتور ها';

    const CREATE_TITLE = 'فاکتور ها - ایجاد';

    const SHOW_TITLE = 'فاکتور ها - نمایش';

    const DEFAULT_DOMAIN = 'https://sample.ir';

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

            $factorData = $this->prepareItemData($request);
            if ($request->input('custom_customer') == 'yes') {
                $user = $this->makeUser($request);
                $project = $this->makeProject($request, $user);
                $factorData['project_id'] = $project->id;
            }

            $factor = Factor::query()->create($factorData);

            foreach ($request->input('item') as $item) {
                resolve(FactorItemCreateJob::class)->handle(
                    $this->setItemValues($factor, $item)
                );
            }

            DB::commit();

            $this->updateFinalPrice($factor);
            $this->updateGatewayBaseUser($factor);

            return $this->successResponse(route('admin.factor.index'));
        } catch (Exception $exception) {

            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function show(Factor $factor)
    {
        $title = self::SHOW_TITLE;

        $factor->load(['items.category', 'project.user', 'admin', 'meta.type.base', 'manual', 'cheque']);

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

        $factorData['project_id'] = $request->input('project_id');

        return $factorData;
    }

    private function makeUser(Request $request): User
    {
        $userData['mobile'] = $request->input('user_mobile');
        $userData['first_name'] = $request->input('user_first_name');
        $userData['last_name'] = $request->input('user_last_name');
        $userData['person_type'] = $request->input('user_person_type');
        $userData['official_bill'] = $request->has('user_official_bill');

        return User::query()->create($userData);
    }

    private function makeProject(Request $request, User $user): Project
    {
        $domains = resolve(DomainTransformer::class);
        $domains->setDomainPrimary(self::DEFAULT_DOMAIN);

        $webData = [
            'package_id' => $request->input('project_package_id'),
            'domains' => $domains->toArray(),
            'host' => resolve(HostTransformer::class)->toArray(),
            'language' => resolve(LanguageTransformer::class)->toArray(),
            'sample' => resolve(SampleTransformer::class)->toArray(),
            'working_days' => 0,
        ];

        $webProject = ProjectWeb::query()->create($webData);

        $projectData = [
            'title' => $request->input('project_title'),
            'price' => $request->input('project_price'),
            'tax_rate' => config('factor.tax'),
            'type_id' => $request->input('project_type_id'),
            'status_id' => $request->input('project_status_id'),
            'agreement_at' => now(),
            'user_id' => $user->id,
            'admin_id' => auth()->id(),
            'base_id' => ProjectBase::Web,
            'domain' => self::DEFAULT_DOMAIN,
        ];

        return $webProject->project()->create($projectData);
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

    private function updateGatewayBaseUser(Factor $factor)
    {
        $user = $factor->project->user;
        $isOfficial = $user->person_type === PersonType::Legal || $user->official_bill;
        $gateway = $isOfficial ? PaymentGateway::SEPEHR : PaymentGateway::PAYPING;

        $factor->update([
            'is_official' => $isOfficial,
            'gateway' => $gateway,
        ]);
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
                ColumnOption::new()->setName('gateway')->setAs('درگاه پرداخت')
            )
            ->addColumn(
                ColumnOption::new()->setName('status')->setAs('وضعیت')
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
            ->addExternalFilter(ExternalFilter::new()->setKey('status'))
            ->addExternalFilter(ExternalFilter::new()->setKey('gateway'))
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
                    'status',
                    'gateway',
                    'paid_at',
                    'created_at',
                ])
                ->filter([
                    PriceFilter::class,
                    StatusFilter::class,
                    ProjectFilter::class,
                    AdminFilter::class,
                    GatewayFilter::class,
                ])
                ->has('project')
                ->with([
                    'admin' => function ($query) {
                        $query->select('admins.id', 'admins.first_name', 'admins.last_name');
                    },
                    'project' => function ($query) {
                        $query->select('projects.id', 'projects.title', 'projects.domain');
                    },
                ])
                ->withoutGlobalScope('project_self_scope');

            return DataTables::eloquent($factors)
                ->editColumn('status', function (Factor $factor) {

                    return factorStatusRender($factor->status, $factor->is_confirm);
                })
                ->editColumn('final_price', function (Factor $factor) {
                    return number_format($factor->final_price);
                })
                ->editColumn('project.title', function (Factor $factor) {
                    return $factor->project_id ? $factor->project->title : 'پروژه ندارد';
                })
                ->editColumn('gateway', function (Factor $factor) {
                    if ($factor->gateway) {
                        return PaymentGateway::getDescription($factor->gateway);
                    }

                    return '';
                })
                ->editColumn('created_at', function (Factor $factor) {
                    return $factor->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->editColumn('paid_at', function (Factor $factor) {
                    return $factor->paid_at?->toJalali()->format(formatJalaliDateTime());
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
