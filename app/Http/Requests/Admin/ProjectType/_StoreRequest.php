<?php

namespace App\Http\Requests\Admin\ProjectType;

use App\Enums\Database\Facility\FinancialCycle;
use App\Enums\Database\Facility\PriceType;
use App\Enums\Database\Facility\WorkCycle;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'facility_id' => ['bail', 'required', 'integer', Rule::exists('facilities', 'id')],
            'price_type' => ['required', new EnumValue(PriceType::class)],
            'price_value' => ['required_if:price_type,3', 'numeric'],

            'work_cycle' => ['required', new EnumValue(WorkCycle::class)],
            'work_cycle_value' => ['required_if:work_cycle,3', 'jdate'],

            'financial_cycle' => ['required', new EnumValue(FinancialCycle::class)],
            'financial_cycle_value' => ['required_if:financial_cycle,2', 'numeric'],

            'added_at' => ['required', 'jdate'],
        ];
    }

    protected function prepareForValidation()
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
