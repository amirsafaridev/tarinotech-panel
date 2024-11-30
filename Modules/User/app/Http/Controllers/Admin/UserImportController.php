<?php

namespace Modules\User\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Modules\User\app\Http\Requests\Admin\User\ImportRequest;
use Modules\User\app\Imports\UserImport;

class UserImportController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'کارفرمایان - بارگذاری';

    public function index()
    {
        $title = self::INDEX_TITLE;

        return view('user::admin.user.import', compact('title'));
    }

    public function import(ImportRequest $request)
    {
        try {
            Excel::import(new UserImport(), request()->file('file'));

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }
}
