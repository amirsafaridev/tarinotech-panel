<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class Select2Controller extends Controller
{
    public function selectAdmin(Request $request)
    {
        $searchTerm = $request->input('term');
        $results = Admin::query()
            ->where('first_name', 'like', '%'.$searchTerm.'%')
            ->orWhere('last_name', 'like', '%'.$searchTerm.'%')
            ->orWhere('email', 'like', '%'.$searchTerm.'%')
            ->get();

        return response()->json($results);
    }

    public function selectUser(Request $request)
    {
        $searchTerm = $request->input('term');
        $results = User::query()
            ->where('first_name', 'like', '%'.$searchTerm.'%')
            ->orWhere('last_name', 'like', '%'.$searchTerm.'%')
            ->orWhere('email', 'like', '%'.$searchTerm.'%')
            ->get();

        return response()->json($results);
    }
}
