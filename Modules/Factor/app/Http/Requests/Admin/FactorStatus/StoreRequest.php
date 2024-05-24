<?php

namespace Modules\Factor\app\Http\Requests\Admin\FactorStatus;

use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Factor\app\Enums\FactorStatus;

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
            'factor_status' => ['required', new EnumValue(FactorStatus::class)],
            'project_status_id' => 'required|exists:project_statuses,id',
            'project_status_forward_id' => 'required|exists:project_statuses,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'factor_status' => (int) $this->input('factor_status'),
        ]);
    }
}
