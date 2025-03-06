<?php

namespace Modules\Admin\app\Http\Requests\Admin\FixedAmount;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'basic_rights' => 'required',
            'right_to_housing' => 'required|numeric',
            'right_to_marry' => 'required|numeric',
            'childrens_right' => 'required|numeric',
            'right_to_eat_and_drink' => 'required|numeric',
            'employer_insurance' => 'nullable|numeric',
            'personnel_insurance' => 'nullable|numeric',
            'employer_insurance_remote' => 'nullable|numeric',
            'personnel_insurance_remote' => 'nullable|numeric',

        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
