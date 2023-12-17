<?php

namespace Modules\Ajax\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Project\app\Models\Project;
use View;

use function response;

class ProjectController extends Controller
{
    public function singleViewItem(Request $request)
    {
        $request->validate([
            'projectId' => 'required|integer',
        ]);

        $projectId = $request->get('projectId');
        $project = Project::query()
            ->with(['user', 'base'])
            ->findOrFail($projectId);

        $projectViewItem = (string) View::make('ajax::rows.project', compact('project'));

        return response()->json([
            'html' => $projectViewItem,
            'message' => 'اطلاعات پروژه بارگیری شد.',
        ]);
    }

    public function remoteSelect(Request $request)
    {
        $searchTerm = $request->input('term');
        $results = Project::query()
            ->select(['id', 'domain', 'title', 'base_id'])
            ->with('base')
            ->where('title', 'like', '%'.$searchTerm.'%')
            ->orWhere('domain', 'like', '%'.$searchTerm.'%')
            ->when(! $searchTerm, function (Builder $q) {
                $q->limit(10);
            })
            ->orderByDesc('id')
            ->get()
            ->map(function (Project $item) {
                $data = $item;
                $data['title'] = $item->title.' ('.$item->base->title.')';

                return $data;
            });

        return response()->json($results);
    }
}
