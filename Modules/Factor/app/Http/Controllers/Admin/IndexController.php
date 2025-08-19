<?php

namespace Modules\Factor\app\Http\Controllers\Admin;

use App\Domain\Jobs\FactorItemCreateJob;
use App\Filters\Admin\Admin\AdminJoinedFilter;
use App\Foundation\ValueObjects\Requests\FactorItemValues;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Service\Json\WebProject\DomainTransformer;
use App\Service\Json\WebProject\HostTransformer;
use App\Service\Json\WebProject\LanguageTransformer;
use App\Service\Json\WebProject\SampleTransformer;
use App\Traits\HasJsonCommonResponseTrait;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Exports\Admin\Report\DatatableExport;
use Modules\Factor\app\Filters\Factor\DateFilter;
use Modules\Factor\app\Filters\Factor\GatewayFilter;
use Modules\Factor\app\Filters\Factor\PriceFilter;
use Modules\Factor\app\Filters\Factor\ProjectFilter;
use Modules\Factor\app\Filters\Factor\ProjectIsSignFilter;
use Modules\Factor\app\Filters\Factor\ProjectIsSignUserFilter;
use Modules\Factor\app\Filters\Factor\ProjectTypeFilter;
use Modules\Factor\app\Filters\Factor\SearchFilter;
use Modules\Factor\app\Filters\Factor\SortFilter;
use Modules\Factor\app\Filters\Factor\StatusFilter;
use Modules\Factor\app\Http\Requests\Admin\Factor\StoreRequest;
use Modules\Factor\app\Models\Factor;
use Modules\Factor\app\Models\FactorItem;
use Modules\Factor\app\Traits\DeterminesBillingDetailsTrait;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectWeb;
use Modules\User\app\Models\User;

class IndexController extends Controller
{
    use DeterminesBillingDetailsTrait,HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'فاکتور ها';

    const CREATE_TITLE = 'فاکتور ها - ایجاد';

    const SHOW_TITLE = 'فاکتور ها - نمایش';

    const DEFAULT_DOMAIN = 'https://sample.ir';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $factors = Factor::query()
            ->select([
                'factors.title',
                'factors.id',
                'factors.identify',
                'factors.final_price',
                'factors.status',
                'factors.is_official',
                'factors.is_automate',
                'factors.is_confirm',
                'factors.created_at',
                'factors.paid_at',
                'factors.gateway',
                'admins.first_name as admin_first_name',
                'admins.last_name as admin_last_name',
                'projects.title as project_title',
                'projects.id as project_id',
                'projects.base_id as project_base_id',
                'projects.domain as project_domain',
                'projects.is_signed as project_is_signed',
                'projects.is_signed_user as project_is_signed_user',
            ])
            ->join('admins', 'factors.admin_id', '=', 'admins.id')
            ->join('projects', 'factors.project_id', '=', 'projects.id')
            ->filter([
                ProjectTypeFilter::class,
                ProjectIsSignFilter::class,
                ProjectIsSignUserFilter::class,
                PriceFilter::class,
                StatusFilter::class,
                ProjectFilter::class,
                AdminJoinedFilter::class,
                GatewayFilter::class,
                DateFilter::class,
                SortFilter::class,
                SearchFilter::class,
            ]);

        if (request('export')) {
            return $this->export($factors->get());
        }

        $factors = $factors->paginate(50)
            ->withQueryString();

        return view('factor::admin.index', compact('title', 'factors'));
    }

    private function export($factors)
    {
        try {
            $fileName = 'Factor-'.Carbon::now()->format('Y-m-d').'.xlsx';

            return Excel::download(new DatatableExport(collect($factors)), $fileName);
        } catch (Exception $exception) {
            report($exception);

            return back()->with('danger', 'خطا در هنگام صادر کردن اطلاعات');
        }
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
        $factor->update([
            'is_official' => $this->isOfficialUser($user),
            'gateway' => $this->determinePaymentGateway($user),
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
}
