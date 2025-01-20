<?php

namespace Modules\Project\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\FacilityCalculate;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Hekmatinasser\Verta\Verta;
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Models\Factor;
use Modules\Factor\app\Models\FactorItem;
use Modules\Factor\app\Traits\DeterminesBillingDetailsTrait;
use Modules\Project\app\Http\Requests\Admin\ProjectFacilityRenewal\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\ProjectFacilityRenewal\UpdateRequest;
use Modules\Project\app\Models\Facility;
use Modules\Project\app\Models\ProjectFacilityRenewal;
use Modules\Project\app\Models\ProjectRenewal;

class ProjectFacilityRenewalController extends Controller
{
    use DeterminesBillingDetailsTrait;
    use HasJsonCommonResponseTrait;

    const CREATE_TITLE = 'امکانات جانبی - ایجاد';

    public function create(int $projectRenewalId)
    {

        $projectRenewal = ProjectRenewal::findWithRelations($projectRenewalId);

        $title = self::CREATE_TITLE;

        $facilities = Facility::query()
            ->where('base_id', $projectRenewal->project->base_id)
            ->get();

        return view('project::admin.project_facility_renewals.create', compact('title', 'projectRenewal', 'facilities'));

    }

    public function store(StoreRequest $request, int $projectRenewalId)
    {
        try {
            DB::beginTransaction();

            $projectRenewal = ProjectRenewal::findOrFail($projectRenewalId);

            $facilityRenewals = collect($request->input('facilities'))
                ->map(fn ($facilityId) => $this->prepareItemData($facilityId, $projectRenewal))
                ->all();

            ProjectFacilityRenewal::insert($facilityRenewals);

            $projectRenewal->project->facilities()->attach(
                collect($request->input('facilities'))
                    ->mapWithKeys(fn ($facilityId) => [
                        $facilityId => ['renewal_at' => now()],
                    ])
                    ->all()
            );

            DB::commit();

            return $this->successResponse(
                route('admin.project.renewal.show', $projectRenewalId)
            );

        } catch (Exception $exception) {
            DB::rollBack();
            report($exception);

            return $this->exceptionResponse($exception);
        }
    }

    protected function prepareItemData($facilityId, ProjectRenewal $renewal): array
    {
        return [
            'facility_id' => $facilityId,
            'project_renewal_id' => $renewal->id,
            'days' => $renewal->project->renewal_at->diffInDays(now()),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function update(UpdateRequest $request, int $projectRenewalId, int $projectFacilityRenewalId)
    {
        try {
            $projectRenewal = ProjectRenewal::findWithRelations($projectRenewalId);

            $workCycleValue = $request->input('work_cycle_value');

            if (! empty($workCycleValue)) {
                $jalaliDate = Verta::parse($workCycleValue);
                $workCycleValue = $jalaliDate->datetime()->format('Y-m-d');
            } else {
                $workCycleValue = null;
            }
            ProjectFacilityRenewal::query()
                ->where('id', $projectFacilityRenewalId)
                ->update([
                    'price_type' => $request->input('price_type'),
                    'price_value' => $request->input('price_value', 0),
                    'work_cycle' => $request->input('work_cycle'),
                    'work_cycle_value' => $workCycleValue,
                    'financial_cycle' => $request->input('financial_cycle'),
                    'financial_cycle_value' => $request->input('financial_cycle_value', 0),
                    'description' => $request->input('description'),
                ]);

            $renewal = ProjectFacilityRenewal::find($projectFacilityRenewalId);

            $renewal->update([
                'calculated_price' => FacilityCalculate::calc($renewal, $projectRenewal->package_calculated_price),
            ]);

            return $this->successUpdateResponse();

        } catch (Exception $exception) {
            report($exception);

            return $this->exceptionResponse($exception);
        }
    }

    public function makeFactor(int $projectRenewalId)
    {
        try {
            $result = DB::transaction(function () use ($projectRenewalId) {
                $projectRenewal = ProjectRenewal::findWithRelations($projectRenewalId);
                $project = $projectRenewal->project;
                $user = $project->user;

                $projectFacilityRenewals = ProjectFacilityRenewal::query()
                    ->with('facility')
                    ->has('facility')
                    ->where('project_renewal_id', $projectRenewalId)
                    ->where('calculated_price', '!=', 0)
                    ->get();

                $projectTitle = sprintf('صدور فاکتور تمدید برای پروژه : %s', $project->title);

                // Calculate tax for main project renewal
                $mainPrice = $projectRenewal->package_calculated_price;
                $mainTaxAmount = $mainPrice * config('factor.tax');
                $mainFinalPrice = $mainPrice + $mainTaxAmount;

                $factor = Factor::create([
                    'title' => $projectTitle,
                    'admin_id' => auth()->id(),
                    'project_id' => $project->id,
                    'final_price' => $mainFinalPrice, // Updated with tax
                    'status' => FactorStatus::Pending,
                    'is_official' => $this->isOfficialUser($user),
                    'is_automate' => true,
                    'gateway_data' => [],
                    'expired_at' => now()->addDays(10),
                    'gateway' => $this->determinePaymentGateway($user),
                ]);

                $items = [[
                    'title' => $projectTitle,
                    'transaction_category_id' => 10,
                    'price' => $mainPrice,
                    'tax_rate' => 0.10,
                    'tax_amount' => $mainTaxAmount,
                    'discount' => 0,
                    'final_price' => $mainFinalPrice,
                    'targetable_type' => ProjectRenewal::class,
                    'targetable_id' => $projectRenewal->id,
                    'factor_id' => $factor->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]];

                if ($projectFacilityRenewals->isNotEmpty()) {
                    foreach ($projectFacilityRenewals as $renewal) {
                        $facilityPrice = $renewal->calculated_price;
                        $facilityTaxAmount = $facilityPrice * 0.10;
                        $facilityFinalPrice = $facilityPrice + $facilityTaxAmount;

                        $items[] = [
                            'title' => $renewal->facility->title,
                            'transaction_category_id' => 10,
                            'price' => $facilityPrice,
                            'tax_rate' => 0.10,
                            'tax_amount' => $facilityTaxAmount,
                            'discount' => 0,
                            'final_price' => $facilityFinalPrice,
                            'targetable_type' => ProjectFacilityRenewal::class,
                            'targetable_id' => $renewal->id,
                            'factor_id' => $factor->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                FactorItem::insert($items);

                $totalPrice = FactorItem::where('factor_id', $factor->id)
                    ->selectRaw('COALESCE(SUM(final_price), 0) as total')
                    ->value('total');

                $factor->update(['final_price' => $totalPrice]);

                return ['factor' => $factor, 'projectRenewal' => $projectRenewal];
            });

            return $this->successBack(
                route('admin.project.renewal.show', $projectRenewalId),
                sprintf('فاکتور به شماره #%d برای پروژه %s با موفقیت صادر شد و در انتظار پرداخت می‌باشد.',
                    $result['factor']->id,
                    $result['projectRenewal']->project->title
                )
            );

        } catch (Exception $exception) {
            report($exception);

            return $this->exceptionBack($exception);
        }
    }

    public function destroy(int $projectRenewalId, int $projectFacilityRenewalId)
    {
        try {
            ProjectRenewal::findWithRelations($projectRenewalId);

            ProjectFacilityRenewal::query()
                ->where('id', $projectFacilityRenewalId)
                ->delete();

            return $this->successDestroyResponse();

        } catch (Exception $exception) {
            report($exception);

            return $this->exceptionResponse($exception);
        }
    }
}
