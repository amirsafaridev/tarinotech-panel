<?php

namespace Modules\Package\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Package\app\Http\Requests\Admin\StoreRequest;
use Modules\Package\app\Http\Requests\Admin\UpdateRequest;
use Modules\Package\app\Models\Package;
use Modules\Package\app\Models\PackageContractHistory;

class PackageController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'پکیج ها';

    const CREATE_TITLE = 'پکیج ها - ایجاد';

    const EDIT_TITLE = 'پکیج ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;
        $packages = Package::query()
            ->with(['finalPrice', 'type.base'])
            ->get();

        return view('package::admin.index', compact('title', 'packages'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('package::admin.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            $package = Package::query()->create($item);
            $package->prices()->create([
                'price' => $request->input('price'),
                'start_at' => now(),
            ]);

            $this->createContractHistoryIfChanged($package, $item);

            DB::commit();

            return $this->successResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Package $package)
    {
        $contractText = $package->contract_text;

        $historyId = request()->integer('restore');
        if ($historyId) {
            $historyText = PackageContractHistory::query()
                ->where('package_id', $package->id)
                ->where('id', $historyId)
                ->value('contract_content');

            if ($historyText) {
                $contractText = $historyText;
            }
        }

        $package->load([
            'finalPrice',
            'prices',
            'contractHistories.admin',
        ]);

        return view('package::admin.edit', [
            'title' => self::EDIT_TITLE,
            'package' => $package,
            'contractText' => $contractText,
        ]);
    }

    public function update(UpdateRequest $request, Package $package)
    {
        try {
            DB::beginTransaction();

            $item = $this->prepareItemData($request);

            $this->createContractHistoryIfChanged($package, $item);

            $package->update($this->prepareItemData($request));
            $latestPackagePrice = $package->load('finalPrice');
            $inputPrice = (int) $request->input('price');

            if ($latestPackagePrice->finalPrice) {
                if ($latestPackagePrice->finalPrice->price !== $inputPrice) {
                    $latestPackagePrice->finalPrice->update(['end_at' => now()]);
                    $package->prices()->create([
                        'price' => $inputPrice,
                        'start_at' => now(),
                    ]);
                }
            } else {
                $package->prices()->create([
                    'price' => $inputPrice,
                    'start_at' => now(),
                ]);
            }

            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Package $package)
    {
        try {
            $package->delete();

            return $this->successDestroyBack(route('admin.package.index'));

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['type_id'] = $request->input('type_id');
        $item['minimum_price_percent'] = $request->input('minimum_price_percent');
        $item['main_unit'] = $request->input('main_unit');

        if ($request->input('base_id') == 2) {
            $item['seo_keywords_count'] = $request->input('seo_keywords_count');
            $item['seo_agreement_duration'] = $request->input('seo_agreement_duration');
            $item['seo_amount_content'] = $request->input('seo_amount_content');
        }

        $item['contract_attachment'] = $request->input('contract_attachment');
        $item['contract_text'] = $request->input('contract_text');
        $item['change_reason'] = $request->input('change_reason');

        return $item;
    }

    private function createContractHistoryIfChanged(Package $package, array $data): void
    {
        if ($package->contract_text !== $data['contract_text']) {
            PackageContractHistory::create([
                'package_id' => $package->id,
                'admin_id' => auth()->id(),
                'contract_content' => $data['contract_text'],
                'change_reason' => $data['change_reason'],
            ]);
        }
    }
}
