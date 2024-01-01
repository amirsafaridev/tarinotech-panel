<?php

namespace Modules\Package\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Package\app\Http\Requests\Admin\StoreRequest;
use Modules\Package\app\Http\Requests\Admin\UpdateRequest;
use Modules\Package\app\Models\Package;

class PackageController extends Controller
{
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'پکیج ها';

    const CREATE_TITLE = 'پکیج ها - ایجاد';

    const EDIT_TITLE = 'پکیج ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $packages = Package::query()
            ->with(['finalPrice', 'type'])
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
            DB::commit();

            return $this->successResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Package $package)
    {

        $title = self::EDIT_TITLE;

        $package->load(['finalPrice', 'prices']);

        return view('package::admin.edit', compact('title', 'package'));
    }

    public function update(UpdateRequest $request, Package $package)
    {
        try {
            DB::beginTransaction();
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

        return $item;
    }
}
