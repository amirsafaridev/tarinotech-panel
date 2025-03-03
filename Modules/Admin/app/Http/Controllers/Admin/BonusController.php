<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Http\Requests\Admin\BonusesDeduction\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\BonusesDeduction\UpdateRequest;
use Exception;
use Modules\Admin\app\Models\BonusesDeduction;
use Modules\Admin\app\Models\Reason;

class BonusController extends Controller
{

    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'پاداش';
    const CREATE_TITLE = 'پاداش - ایجاد';
    const EDIT_TITLE = 'پاداش - ویرایش';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = self::INDEX_TITLE;
        $bonuses = BonusesDeduction::query()->get();
        return view('admin::admin.bonuses.index', compact('title', 'bonuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('admin::admin.bonuses.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $inputs['user_id'] = Auth::user()->id;
            $inputs['type'] = 0;
            $bonus =  BonusesDeduction::query()->create($inputs);
            Reason::query()->create([
                'bonuses_deduction_id' => $bonus->id,
                'description' => $inputs['description']
            ]);
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
    public function edit(BonusesDeduction $bonus)
    {
        $title = self::EDIT_TITLE;

        return view('admin::admin.bonuses.edit', compact('title', 'bonus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, BonusesDeduction $bonus)
    {
        try {
            DB::beginTransaction();
            $bonus->update($request->all());
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
    public function destroy(BonusesDeduction $bonus)
    {
        try {
            $bonus->delete();

            return $this->successDestroyBack(route('admin.admin.bonuses.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
}
