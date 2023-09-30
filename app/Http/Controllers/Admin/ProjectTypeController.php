<?php

namespace App\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ProjectType;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class ProjectTypeController extends Controller
{
    public function index()
    {
        $title = 'انواع پزوژه ها';
        $routeData = route('admin.project-type.data');
        $selects = ['id', 'title', 'projects_count', 'created_at'];

        return view('admin.project_type.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {

        try {
            $roles = ProjectType::query()
                ->withCount('projects');

            return DataTables::of($roles)
                ->editColumn('created_at', function ($role) {
                    return $role->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function ($role) {
                    return Helper::btnMaker(BtnType::Info, route('admin.project-type.show', $role->id), trans('panel.action.show'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function show(ProjectType $projectType)
    {
        $title = 'نمایش نوع';
        $projectType->loadCount('projects');

        return view('admin.project_type.show', compact('title', 'projectType'));
    }
}
