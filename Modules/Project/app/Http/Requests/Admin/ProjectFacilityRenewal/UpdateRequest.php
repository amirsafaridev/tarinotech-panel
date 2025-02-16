<?php

namespace Modules\Project\App\Http\Requests\Admin\ProjectFacilityRenewal;

use App\Enums\Database\Facility\FinancialCycle;
use App\Enums\Database\Facility\PriceType;
use App\Enums\Database\Facility\WorkCycle;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'price_type' => ['required', new EnumValue(PriceType::class)],
            'price_value' => ['required_if:price_type,3', 'numeric'],

            'work_cycle' => ['required', new EnumValue(WorkCycle::class)],
            'work_cycle_value' => ['required_if:work_cycle,3', 'jdate'],

            'financial_cycle' => ['required', new EnumValue(FinancialCycle::class)],
            'financial_cycle_value' => ['required_if:financial_cycle,2', 'numeric'],

        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'price_type' => (int) $this->input('price_type'),
            'work_cycle' => (int) $this->input('work_cycle'),
            'financial_cycle' => (int) $this->input('financial_cycle'),
            'price_value' => str_replace(',', '', $this->input('price_value')),
            'financial_cycle_value' => str_replace(',', '', $this->input('financial_cycle_value')),
        ]);
    }
}
