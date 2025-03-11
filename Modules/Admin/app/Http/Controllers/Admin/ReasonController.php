<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Http\Requests\Admin\Reason\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\Reason\UpdateRequest;
use Exception;
use Modules\Admin\app\Models\Reason;

class ReasonController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'علت ها';
    const CREATE_TITLE = 'علت - ایجاد';
    const EDIT_TITLE = 'علت - ویرایش';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = self::INDEX_TITLE;
        $reasons = Reason::query()->get();
        return view('admin::admin.reason.index', compact('title', 'reasons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('admin::admin.reason.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            Reason::query()->create($inputs);
            DB::commit();

            return $this->successResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    /**
     * Show the specified resource.
    //  */
    // public function show($id)
    // {
    //     return view('admin::show');
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reason $reason)
    {
        $title = self::EDIT_TITLE;

        return view('admin::admin.reason.edit', compact('title', 'reason'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Reason $reason)
    {
        try {
            DB::beginTransaction();
            $reason->update($request->all());
            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reason $reason)
    {
        try {
            $reason->delete();

            return $this->successDestroyBack(route('admin.admin.reason.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
}
