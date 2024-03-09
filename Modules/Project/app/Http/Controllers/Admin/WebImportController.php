<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Project\app\Http\Requests\Admin\Web\ImportRequest;
use Modules\Project\app\Imports\ProjectWebImport;

class WebImportController extends Controller
{
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'پروژه وب - بارگذاری';

    public function index()
    {
        $title = self::INDEX_TITLE;

        return view('project::admin.web.import', compact('title'));
    }

    public function import(ImportRequest $request)
    {
        try {
            Excel::import(new ProjectWebImport(), request()->file('file'));

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }
}
