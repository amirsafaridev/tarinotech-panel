<?php

namespace Modules\Factor\app\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Http\Requests\Admin\Factor\UpdateRequest;
use Modules\Factor\app\Models\Factor;
use Modules\Factor\app\Models\FactorCheque;
use Modules\Factor\app\Models\FactorItem;
use Modules\Factor\app\Models\FactorManualInfo;
use Modules\Factor\app\Models\TransactionCategory;
use Modules\Factor\app\Traits\WithAttributeChange;

class UpdateController extends Controller
{
    use HasJsonCommonResponse;
    use WithAttributeChange;

    const EDIT_TITLE = 'فاکتور ها - ویرایش';

    public function index(Factor $factor)
    {
        $factor->load(['items', 'project', 'meta', 'cheque', 'manual']);

        $isFreeze = $this->isFreeze($factor);

        $title = self::EDIT_TITLE;
        $categories = TransactionCategory::query()->get();

        return view('factor::admin.edit', compact('title', 'factor', 'categories', 'isFreeze'));
    }

    public function update(UpdateRequest $request, Factor $factor)
    {
        try {
            if ($this->isFreeze($factor)) {
                return $this->failure('این فاکتور قابل ویرایش نیست.', 400);
            }
            DB::beginTransaction();
            $factor->load('items');

            $this->syncAttributes($request, $factor);

            $finalPrice = $this->updateFinalPrice($factor);

            $updatedAttributes = $this->prepareItemData($request, true);
            $updatedAttributes['status'] = $request->input('status');

            if ($request->input('status') == FactorStatus::PaidWithCheque) {

                if ($finalPrice != $request->input('cheque_amount')) {
                    DB::rollBack();

                    return $this->failure('مبلغ چک باید برابر با مجموع آیتم فاکتور مشخص شده باشد.', 400);
                }

                $factorCheques = $this->createOrUpdateCheque($factor, $request);
                $updatedAttributes['paid_at'] = $factorCheques->payment_date;

            }

            if ($request->input('status') == FactorStatus::PaidManual) {
                $factorManualInfos = $this->createOrUpdateManual($factor, $request);
                $updatedAttributes['paid_at'] = $factorManualInfos->payment_date;
            }

            $factor->update($updatedAttributes);

            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    protected function prepareItemData(Request $request, bool $isEditMode = false): array
    {
        $factorData['title'] = $request->input('title');
        $factorData['expired_at'] = Helper::toGregorian($request->input('expired_at'));
        $factorData['status'] = FactorStatus::Pending;
        $factorData['project_id'] = $request->input('project_id');

        return $factorData;
    }

    private function updateFinalPrice(Factor $factor)
    {
        $finalPrice = FactorItem::query()
            ->where('factor_id', $factor->id)
            ->sum('final_price');

        $factor->update([
            'final_price' => $finalPrice,
        ]);

        return $finalPrice;
    }

    private function isFreeze(Factor $factor): bool
    {
        if (hasAdminRole(1)) {
            return false;
        }
        if (! in_array($factor->status, [
            FactorStatus::Paid,
            FactorStatus::PaidWithCheque,
            FactorStatus::PaidManual,
        ])) {
            return false;
        }

        return true;
    }

    private function createOrUpdateCheque(Factor $factor, UpdateRequest $request): FactorCheque
    {
        $amount = $request->input('cheque_amount');
        $paymentDate = Helper::toGregorian($request->input('cheque_payment_date'));
        $chequeIdentifier = $request->input('cheque_identifier');
        $chequeRegistered = $request->has('cheque_registered');
        $factorId = $factor->id;

        $factorCheque = FactorCheque::firstOrNew(['factor_id' => $factorId]);

        if ($request->hasFile('cheque_file')) {
            $chequeFilePath = $request->file('cheque_file')->store('cheque_file', 'private');
        } else {
            $chequeFilePath = $factorCheque->cheque_file;
        }

        $chequeData = [
            'amount' => $amount,
            'payment_date' => $paymentDate,
            'cheque_identifier' => $chequeIdentifier,
            'cheque_file' => $chequeFilePath,
            'cheque_registered' => $chequeRegistered,
            'factor_id' => $factorId,
        ];

        $factorCheque->fill($chequeData)->save();

        return $factorCheque;
    }

    private function createOrUpdateManual(Factor $factor, UpdateRequest $request): FactorManualInfo
    {
        $paymentDate = Helper::toGregorian($request->input('manual_payment_date'));
        $factorId = $factor->id;

        $factorManualInfo = FactorManualInfo::firstOrNew(['factor_id' => $factorId]);

        if ($request->hasFile('manual_file')) {
            $filePath = $request->file('manual_file')->store('manual_file', 'private');
        } else {
            $filePath = $factorManualInfo->file;
        }

        $manualData = [
            'payment_date' => $paymentDate,
            'file' => $filePath,
            'factor_id' => $factorId,
        ];

        $factorManualInfo->fill($manualData)->save();

        return $factorManualInfo;
    }
}
