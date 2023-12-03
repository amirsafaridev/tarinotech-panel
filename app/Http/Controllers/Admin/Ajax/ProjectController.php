<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Project\app\Models\Project;
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

        $projectViewItem = (string) View::make('admin.project.rows.single-factor', compact('project'));

        return response()->json([
            'html' => $projectViewItem,
            'message' => 'اطلاعات پروژه بارگیری شد.',
        ]);
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
