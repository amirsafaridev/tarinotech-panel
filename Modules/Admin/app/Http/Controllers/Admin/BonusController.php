<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Http\Requests\Admin\BonusesDeduction\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\BonusesDeduction\UpdateRequest;
use Exception;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\app\Models\BonusesDeduction;
use Modules\Admin\app\Models\Reason;
use Modules\User\app\Models\User;

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
        $bonuses = BonusesDeduction::query()->where('type', 0)->get();
        return view('admin::admin.bonuses.index', compact('title', 'bonuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = self::CREATE_TITLE;
        $users = Admin::query()->get();
        return view('admin::admin.bonuses.create', compact('title', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
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
    public function edit(BonusesDeduction $bonusesDeduction)
    {
        $title = self::EDIT_TITLE;
        $reason = Reason::where('bonuses_deduction_id', $bonusesDeduction->id)->latest()->first();
        $users = Admin::query()->get();

        return view('admin::admin.bonuses.edit', compact('title', 'bonusesDeduction', 'reason', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, BonusesDeduction $bonusesDeduction)
    {
        try {
            $reason = Reason::where('bonuses_deduction_id', $bonusesDeduction->id)->latest()->first();
            DB::beginTransaction();
            $bonusesDeduction->update($request->all());
            $reason->update([
                'bonuses_deduction_id' => $bonusesDeduction->id,
                'description' => $request->description
            ]);

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
    public function destroy(BonusesDeduction $bonusesDeduction)
    {
        try {

            $bonusesDeduction->delete();
            foreach ($bonusesDeduction->reasons()->get() as $reason) {
                $reason->delete();
            }

            return $this->successDestroyBack(route('admin.admin.bonuses.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
}
