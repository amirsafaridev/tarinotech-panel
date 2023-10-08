<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Package\StoreRequest;
use App\Http\Requests\Admin\Package\UpdateRequest;
use App\Models\Package;
use DB;
use Exception;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $title = 'پیکج ها';
        $packages = Package::query()->with('finalPrice')->get();

        return view('admin.package.index', compact('title', 'packages'));
    }

    public function create()
    {
        $title = 'پیکج جدید';
        $routeStore = route('admin.package.store');

        return view('admin.package.create', compact('title', 'routeStore'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $package = Package::create($item);
            $package->prices()->create([
                'price' => $request->input('price'),
                'start_at' => now(),
            ]);
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
                'message' => trans('panel.error_store'),
            ], 500);
        }
    }

    public function edit(Package $package)
    {

        $title = 'ویرایش وضعیت';
        $routeUpdate = route('admin.package.update', $package->id);
        $routeDestroy = route('admin.package.destroy', $package->id);

        $package->load(['finalPrice', 'prices']);

        return view('admin.package.edit', compact('title', 'routeUpdate', 'routeDestroy', 'package'));
    }

    public function update(UpdateRequest $req, Package $package)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($req);
            $package->update($item);
            $latestPackagePrice = $package->load('finalPrice');
            $inputPrice = (int) $req->input('price');

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
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Package $package)
    {
        try {
            $package->delete();

            return redirect(route('admin.package.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.package.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['title'] = $request->input('title');

        return $item;
    }
}
