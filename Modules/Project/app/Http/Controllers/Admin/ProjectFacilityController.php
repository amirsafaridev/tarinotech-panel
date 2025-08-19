<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\Database\Facility\FinancialCycle;
use App\Enums\Database\Facility\PriceType;
use App\Enums\Database\Facility\WorkCycle;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Models\Admin;
use Modules\Project\app\Http\Requests\Admin\Type\UpdateRequest;
use Modules\Project\app\Models\Facility;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectBase;
use Modules\Project\app\Models\ProjectFacility;
use Modules\Project\app\Models\ProjectType;
use  Modules\Project\app\Http\Requests\Admin\ProjectFacility\StoreRequest;

class ProjectFacilityController extends Controller
{
    use HasJsonCommonResponseTrait;

    // public function index(Project $project)
    // {
    //     $title = 'امکانات جانبی';
    //     $facilities = ProjectFacility::query()
    //         ->with('facility')
    //         ->where('project_id', $project->id)
    //         ->get();

    //     return view('admin.project_facility.index', compact('title', 'project', 'facilities'));
    // }

    // public function create(Project $project)
    // {
    //     $title = 'امکانات جانبی - ایجاد';
    //     $routeStore = route('admin.project.facility.store', $project->id);
    //     $facilities = Facility::query()->get();

    //     return view('admin.project.project_facility.create', compact('title', 'routeStore', 'project', 'facilities','users'));
    // }
    public function add(Project $project)
    {
        $title = 'امکان جانبی - افزودن';
        $routeStore = route('admin.project.project_facilities.store', $project->id);
        $facilities = Facility::query()->get();
        $users = Admin::query()->get();
        return view('project::admin.project_facility.add', compact('title', 'routeStore', 'project', 'facilities', 'users'));
    }

    public function store(Project $project, StoreRequest $request, $type = 'web')
    {
        $inputs = $request->all();
        try {
            $facilities = collect($request->input('facilities'))->mapWithKeys(function ($facilityId) use ($inputs) {
                return [
                    $facilityId => [
                        'renewal_at' => now(),
                        'user_id' => $inputs['user_id']
                    ],
                ];
            });

            $project->facilities()->sync($facilities);
            $user = $inputs['user_id'];
            if ($type === 'web') {
                $factor = new WebFactorController;
                $redirectRoute = route('admin.project.web.edit.status', $project->id);
            } else {
                $factor = new SeoFactorController;
                $redirectRoute = route('admin.project.seo.edit.status', $project->id);

            }
            $fullFacilities = Facility::whereIn('id', array_keys($facilities->toArray()))->get();

            foreach ($fullFacilities as $facilityId => $facility) {
                $factor->makeFacilitiesFactore($project->id, $user, $facility);
            }

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_store'),
                'back' =>  $redirectRoute ,

            ]);
        } catch (Exception $e) {

            DB::rollBack();

            return $this->exceptionResponse($e);
        }
    }

    // public function edit(Project $project, ProjectFacility $projectFacility)
    // {
    //     $title = 'انواع پروژه ها - ویرایش';
    //     $routeUpdate = route('admin.project.facility.update', $projectFacility->id);
    //     $routeDestroy = route('admin.project.facility.destroy', $projectFacility->id);
    //     $projectBases = ProjectBase::query()->get();

    //     return view('admin.project_facility.edit', compact('title', 'routeUpdate', 'routeDestroy', 'projectFacility', 'projectBases'));
    // }

    // public function update(UpdateRequest $request, ProjectType $projectType)
    // {
    //     try {

    //         $item = $this->itemProvider($request);
    //         $projectType->update($item);

    //         return response()->json([
    //             'result' => 'success',
    //             'message' => trans('panel.success_update'),
    //         ]);
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         report($e);

    //         return response()->json([
    //             'result' => 'exception',
    //             'message' => trans('panel.error_update'),
    //         ], 500);
    //     }
    // }

    // public function destroy(ProjectType $projectType)
    // {
    //     try {
    //         $projectType->delete();

    //         return redirect(route('admin.project.type.index'))->with('success', trans('panel.success_delete'));
    //     } catch (Exception $e) {
    //         report($e);

    //         return redirect(route('admin.project.type.index'))->with('danger', trans('panel.error_delete'));
    //     }
    // }

    // protected function itemProvider(Request $req, int $projectId): array
    // {
    //     $item['project_id'] = $projectId;
    //     $item['facility_id'] = $req->input('facility_id');
    //     $item['price_type'] = $req->input('price_type');
    //     $item['price_value'] = (int) $req->input('price_value', 0);
    //     $item['work_cycle'] = $req->input('work_cycle');
    //     $workCycleValue = $req->input('work_cycle_value');
    //     $item['work_cycle_value'] = empty($workCycleValue) ? null : $workCycleValue;
    //     $item['financial_cycle'] = $req->input('financial_cycle');
    //     $item['financial_cycle_value'] = (int) $req->input('financial_cycle_value', 0);
    //     $item['added_at'] = Helper::toGregorian($req->input('added_at'));
    //     $item['description'] = $req->input('description');

    //     return $item;
    // }

    // private function isPossibleModes(Request $request): bool
    // {
    //     $priceType = (int) $request->input('price_type');
    //     $workCycle = (int) $request->input('work_cycle');
    //     $financialCycle = (int) $request->input('financial_cycle');

    //     if (
    //         $priceType === PriceType::None &&
    //         $workCycle === WorkCycle::None &&
    //         $financialCycle === FinancialCycle::CalcFromPackage
    //     ) {
    //         return false;
    //     }

    //     if (
    //         $priceType === PriceType::Package &&
    //         $workCycle === WorkCycle::None &&
    //         $financialCycle === FinancialCycle::TwentyPercentCreationPrice
    //     ) {
    //         return false;
    //     }

    //     if (
    //         $priceType === PriceType::Input &&
    //         $workCycle === WorkCycle::None &&
    //         $financialCycle === FinancialCycle::CalcFromPackage
    //     ) {
    //         return false;
    //     }

    //     if (
    //         $priceType === PriceType::None &&
    //         $workCycle === WorkCycle::Yearly &&
    //         $financialCycle === FinancialCycle::CalcFromPackage
    //     ) {
    //         return false;
    //     }

    //     if (
    //         $priceType === PriceType::None &&
    //         $workCycle === WorkCycle::InputDate &&
    //         $financialCycle === FinancialCycle::CalcFromPackage
    //     ) {
    //         return false;
    //     }

    //     if (
    //         $priceType === PriceType::Package &&
    //         $workCycle === WorkCycle::Yearly &&
    //         $financialCycle === FinancialCycle::TwentyPercentCreationPrice
    //     ) {
    //         return false;
    //     }

    //     if (
    //         $priceType === PriceType::Package &&
    //         $workCycle === WorkCycle::InputDate &&
    //         $financialCycle === FinancialCycle::TwentyPercentCreationPrice
    //     ) {
    //         return false;
    //     }

    //     if (
    //         $priceType === PriceType::Input &&
    //         $workCycle === WorkCycle::Yearly &&
    //         $financialCycle === FinancialCycle::CalcFromPackage
    //     ) {
    //         return false;
    //     }

    //     if (
    //         $priceType === PriceType::Input &&
    //         $workCycle === WorkCycle::InputDate &&
    //         $financialCycle === FinancialCycle::CalcFromPackage
    //     ) {
    //         return false;
    //     }

    //     return true;
    // }

    // private function getImpossibleResponse(): JsonResponse
    // {
    //     return response()->json([
    //         'result' => 'error',
    //         'message' => 'این حالت قابل ثبت نیست',
    //     ]);
    // }
}
