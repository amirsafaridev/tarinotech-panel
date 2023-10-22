<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use View;

class ProjectController extends Controller
{
    public function singleViewItem(Request $request)
    {
        $request->validate([
            'projectId' => 'required|integer',
        ]);

        $projectId = $request->get('projectId');
        $project = Project::query()
            ->with('user')
            ->findOrFail($projectId);

        return View::make('admin.project.rows.single-factor', compact('project'));
    }

    public function remoteSelect(Request $request)
    {
        $searchTerm = $request->input('term');
        $results = Project::query()
            ->where('title', 'like', '%'.$searchTerm.'%')
            ->orWhere('domain', 'like', '%'.$searchTerm.'%')
            ->when(! $searchTerm, function (Builder $q) {
                $q->limit(10);
            })
            ->orderByDesc('id')
            ->get();

        return response()->json($results);
    }
}
