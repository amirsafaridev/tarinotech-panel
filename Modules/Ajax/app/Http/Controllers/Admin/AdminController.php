<?php

namespace Modules\Ajax\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\app\Models\Admin;

use function response;

class AdminController extends Controller
{
    public function remoteSelect(Request $request)
    {
        $searchTerm = $request->input('term');
        $results = Admin::query()
            ->where('first_name', 'like', '%'.$searchTerm.'%')
            ->orWhere('last_name', 'like', '%'.$searchTerm.'%')
            ->orWhere('email', 'like', '%'.$searchTerm.'%')
            ->get();

        return response()->json($results);
    }
}
