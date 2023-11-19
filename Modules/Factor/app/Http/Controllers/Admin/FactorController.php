<?php

namespace Modules\Factor\app\Http\Controllers\Admin;

use App\Domin\Jobs\FactorItemCreateJob;
use App\Domin\Jobs\FactorItemUpdateJob;
use App\Enums\Database\Factor\FactorStatus;
use App\Filters\Admin\Share\IDFilter;
use App\Filters\Admin\Share\TitleFilter;
use App\Foundation\ValueObjects\Requests\FactorItemValues;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Factor\app\Filters\Factor\PriceFilter;
use Modules\Factor\app\Filters\Factor\SortFilter;
use Modules\Factor\app\Http\Requests\Admin\Factor\StoreRequest;
use Modules\Factor\app\Http\Requests\Admin\Factor\UpdateRequest;
use Modules\Factor\app\Models\Factor;
use Modules\Factor\app\Models\FactorItem;
use Modules\Factor\app\Models\TransactionCategory;
use Modules\User\app\Enums\PersonType;

class FactorController extends Controller
{
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'فاکتور ها';

    const CREATE_TITLE = 'فاکتور ها - ایجاد';

    const EDIT_TITLE = 'فاکتور ها - ویرایش';

    const SHOW_TITLE = 'فاکتور ها - نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $factors = Factor::query()
            ->with(['project.user', 'admin'])
            ->filter([
                IDFilter::class,
                TitleFilter::class,
                PriceFilter::class,
                SortFilter::class,
            ])
            ->paginate(12);

        $sortItems = [
            'id-desc' => 'شناسه (نزولی)',
            'id-asc' => 'شناسه (صعودی)',
            'final_price-desc' => 'قیمت (نزولی)',
            'final_price-asc' => 'قیمت (صعودی)',
        ];

        return view('factor::admin.index', compact('title', 'factors', 'sortItems'));
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
        $factor->load(['items', 'project']);

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

        $factor->load(['items.category', 'project.user', 'admin']);

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
        $item['project_id'] = $request->input('project_id');
        $item['title'] = $request->input('title');

        $item['expired_at'] = Helper::toGregorian($request->input('expired_at'));
        $item['status'] = FactorStatus::Pending;

        $item['gateway_data'] = [];
        $item['admin_id'] = auth()->id();

        $item['is_official'] = false;

        $project = Project::with('user')->find($item['project_id']);
        if ($project) {
            $user = $project->user;
            if ($user && ($user->person_type === PersonType::Legal || $user->official_bill)) {
                $item['is_official'] = true;
            }
        }

        return $item;
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
