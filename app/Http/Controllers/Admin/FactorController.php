<?php

namespace App\Http\Controllers\Admin;

use App\Domin\Jobs\FactorItemCreateJob;
use App\Domin\Jobs\FactorItemUpdateJob;
use App\Enums\Database\Factor\FactorStatus;
use App\Enums\Database\User\PersonType;
use App\Filters\Admin\Factor\PriceFilter;
use App\Filters\Admin\Project\SortFilter;
use App\Filters\Admin\Share\IDFilter;
use App\Filters\Admin\Share\TitleFilter;
use App\Foundation\ValueObjects\Requests\FactorItemValues;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Factor\StoreRequest;
use App\Http\Requests\Admin\Factor\UpdateRequest;
use App\Models\Factor;
use App\Models\FactorItem;
use App\Models\Project;
use App\Models\TransactionCategory;
use DB;
use Exception;
use Illuminate\Http\Request;

class FactorController extends Controller
{
    public function index()
    {
        $title = 'فاکتور ها';

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

        return view('admin.factor.index', compact('title', 'factors', 'sortItems'));
    }

    public function create()
    {
        $title = 'فاکتور ها - ایجاد';
        $routeStore = route('admin.factor.store');

        return view('admin.factor.create', compact('title', 'routeStore'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $factor = Factor::create($this->itemProvider($request));
            foreach ($request->input('item') as $item) {
                resolve(FactorItemCreateJob::class)->handle(
                    $this->getSetItem($factor, $item)
                );
            }

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_store'),
            ]);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Factor $factor)
    {
        $factor->load(['items', 'project']);

        $title = 'فاکتور ها - ویرایش';
        $routeUpdate = route('admin.factor.update', $factor->id);
        $routeDestroy = route('admin.factor.destroy', $factor->id);
        $transactionCategories = TransactionCategory::query()->get();

        return view('admin.factor.edit', compact('title', 'routeUpdate', 'routeDestroy', 'factor', 'transactionCategories'));
    }

    public function update(UpdateRequest $request, Factor $factor)
    {
        try {

            DB::beginTransaction();
            $factor->load('items');
            $updatedItemIds = [];

            foreach ($request->input('item') as $item) {
                $factorItemValues = $this->getSetItem($factor, $item);

                $action = $item['action'];

                if ($action === 'store') {
                    resolve(FactorItemCreateJob::class)->handle($factorItemValues);
                } else {
                    $updatedItemIds[] = $item['id'];
                    $factorItemId = $item['id'];
                    resolve(FactorItemUpdateJob::class)->handle($factorItemValues, $factorItemId);
                }

            }

            $currentItemIds = $factor->items->pluck('id')->toArray();
            $deletedItemIds = array_diff($currentItemIds, $updatedItemIds);
            if (count($deletedItemIds)) {
                FactorItem::query()
                    ->whereIn('id', $deletedItemIds)
                    ->delete();
            }

            $updatedAttributes = $this->itemProvider($request);
            $updatedAttributes['status'] = $request->input('status');
            $factor->update($updatedAttributes);

            DB::commit();

            return response()->json([
                'result' => 'success',
                'refresh' => true,
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

    public function show(Factor $factor)
    {
        $title = 'نمایش فاکتور';

        $factor->load(['items.transactionCategory', 'project.user', 'admin']);

        return view('admin.factor.show', compact('title', 'factor'));
    }

    public function destroy(Factor $factor)
    {
        try {
            $factor->delete();

            return redirect(route('admin.factor.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return redirect(route('admin.factor.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
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

    private function getSetItem(Factor $factor, array $item): FactorItemValues
    {
        return resolve(FactorItemValues::class)
            ->setFactorId($factor->id)
            ->setTitle($item['title'])
            ->setTransactionCategoryId($item['transaction_category_id'])
            ->setPrice($item['price'])
            ->setDiscount($item['discount']);
    }
}
