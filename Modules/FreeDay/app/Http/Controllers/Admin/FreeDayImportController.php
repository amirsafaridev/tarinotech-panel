<?php

namespace Modules\FreeDay\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Modules\FreeDay\app\Http\Requests\Admin\ImportRequest;
use Modules\FreeDay\app\Imports\FreeDayImport;

class FreeDayImportController extends Controller
{
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'تقویم تعطیلات - بارگذاری';

    public function index()
    {
        $title = self::INDEX_TITLE;

        return view('freeday::admin.import.index', compact('title'));
    }

    public function import(ImportRequest $request)
    {
        try {
            Excel::import(new FreeDayImport, request()->file('file'));

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }
}
