<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Database\Factor\FactorStatus;
use App\Enums\Database\User\PersonType;
use App\Filters\Admin\Factor\PriceFilter;
use App\Filters\Admin\Project\SortFilter;
use App\Filters\Admin\Share\IDFilter;
use App\Filters\Admin\Share\TitleFilter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Factor\StoreRequest;
use App\Http\Requests\Admin\Factor\UpdateRequest;
use App\Models\Admin;
use App\Models\Factor;
use App\Models\FactorItem;
use App\Models\Project;
use App\Models\ProjectAds;
use App\Models\TransactionCategory;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
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

    public function _index()
    {
        $title = 'فاکتور ها';
        $sortItems = [
            'id-desc' => 'شناسه (نزولی)',
            'id-asc' => 'شناسه (صعودی)',
            'price-desc' => 'قیمت (نزولی)',
            'price-asc' => 'قیمت (صعودی)',
        ];

        /*$projects = Project::query()
            ->with(['status', 'type', 'user', 'admin'])
            ->whereHasMorph('type', [ProjectAds::class])
            ->whereHas('user', function (Builder $q) {
                $q->filter([
                    UserSearchFilter::class,
                ]);
            })
            ->filter([
                IDFilter::class,
                DomainFilter::class,
                StatusFilter::class,
                SortFilter::class,
            ])
            ->paginate(12);

        $sortItems = [
            'id-desc' => 'شناسه (نزولی)',
            'id-asc' => 'شناسه (صعودی)',
            'price-desc' => 'قیمت (نزولی)',
            'price-asc' => 'قیمت (صعودی)',
        ];*/

        return view('admin.factor.index', compact('title', 'sortItems'));
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
            DB::beginTransaction();

            $factor = Factor::create($this->itemProvider($request));

            $taxRate = 0.09;

            foreach ($request->input('item') as $item) {
                $price = $item['price'];
                $taxAmount = $price * $taxRate;
                $finalPrice = ($price + $taxAmount) - $item['discount'];

                $factor->items()->create([
                    'title' => $item['title'],
                    'transaction_category_id' => $item['transaction_category_id'],
                    'price' => $price,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'discount' => $item['discount'],
                    'final_price' => $finalPrice,
                ]);
            }

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
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Factor $factor)
    {
        $factor->load(['items', 'project']);

        $title = 'فاکتور ها - ویرایش';
        $routeUpdate = route('admin.factor.update', $factor->id);
        $transactionCategories = TransactionCategory::query()->get();

        return view('admin.factor.edit', compact('title', 'routeUpdate', 'factor', 'transactionCategories'));
    }

    public function update(UpdateRequest $request, Factor $factor)
    {
        try {

            DB::beginTransaction();
            $factor->load('items');

            $taxRate = 0.09;
            $updatedItemIds = [];

            foreach ($request->input('item') as $item) {
                $price = $item['price'];
                $taxAmount = $price * $taxRate;
                $finalPrice = ($price + $taxAmount) - $item['discount'];
                $action = $item['action'];

                $itemParams = [
                    'title' => $item['title'],
                    'transaction_category_id' => $item['transaction_category_id'],
                    'price' => $price,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'discount' => $item['discount'],
                    'final_price' => $finalPrice,
                ];

                if ($action === 'store') {
                    $factor->items()->create($itemParams);
                } else {
                    $updatedItemIds[] = $item['id'];
                    $factorItemId = $item['id'];
                    FactorItem::query()
                        ->where('id', $factorItemId)
                        ->update($itemParams);
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

    public function show(Admin $admin)
    {
        $title = trans('panel.admin.show');

        return view('admin.admin.show', compact('title', 'admin'));
    }

    public function destroy(Admin $admin)
    {
        try {
            DB::beginTransaction();
            $admin->update(['email' => uniqid($admin->email).'_']);
            $admin->delete();
            DB::commit();

            return redirect(route('admin.admin.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return redirect(route('admin.admin.index'))->with('danger', trans('panel.error_delete'));
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
}
