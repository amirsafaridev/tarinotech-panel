<?php

namespace Modules\Ajax\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
}
