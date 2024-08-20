<?php

namespace Modules\Ajax\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Modules\Ajax\app\Http\Requests\Package\CurrentRequest;
use Modules\Package\app\Models\Package;

class PackageController extends Controller
{
    public function byType(Request $request)
    {
        $typeId = $request->input('type-id');
        $packages = Package::query()
            ->select(['id', 'title'])
            ->where('type_id', (int) $typeId)
            ->get();

        return response()->json([
            'packages' => $packages,
        ]);
    }

    public function currentPrice(CurrentRequest $request)
    {
        try {
            $currentDate = Verta::parse($request->input('date'))->toCarbon();

            $package = Package::query()
                ->where('id', $request->get('package_id'))
                ->first();

            $currentPrice = $package->getPriceForDate($currentDate);

            return response()->json([
                'data' => $currentPrice->toArray(),
                'success' => false,
            ]);

        } catch (\Exception $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'خطایی رخ داده است!',
            ], 500);
        }
    }
}
