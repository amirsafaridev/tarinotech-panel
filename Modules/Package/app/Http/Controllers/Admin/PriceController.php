<?php

namespace Modules\Package\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackagePrice\UpdateRequest;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Package\app\Models\Package;
use Modules\Package\app\Models\PackagePrice;

use function redirect;
use function report;
use function response;
use function route;
use function trans;
use function view;

class PriceController extends Controller
{
    public function edit(Package $package, PackagePrice $packagePrice)
    {
        $title = 'ویرایش مبلغ پکیج';
        $routeUpdate = route('admin.package-price.update', [$package->id, $packagePrice->id]);
        $routeDestroy = route('admin.package-price.destroy', [$package->id, $packagePrice->id]);

        return view('admin.package_price.edit', compact('title', 'routeUpdate', 'routeDestroy', 'package', 'packagePrice'));
    }

    public function update(UpdateRequest $request, Package $package, PackagePrice $packagePrice)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $packagePrice->update($item);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'back' => route('admin.package.edit', $package->id),
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

    public function destroy(Package $package, PackagePrice $packagePrice)
    {
        try {
            $packagePrice->delete();

            return redirect(route('admin.package.edit', $package->id))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.package.edit', $package->id))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['price'] = $request->input('price');

        return $item;
    }
}
