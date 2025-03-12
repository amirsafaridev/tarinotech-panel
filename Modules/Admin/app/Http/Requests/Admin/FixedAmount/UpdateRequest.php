<?php

namespace Modules\Admin\app\Http\Requests\Admin\FixedAmount;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'date' => 'required',
            'basic_rights' => 'required|integer',
            'right_to_housing' => 'required|integer',
            'right_to_marry' => 'required|integer',
            'childrens_right' => 'required|integer',
            'right_to_eat_and_drink' => 'required|integer',
            'employer_insurance' => 'required|integer',
            'personnel_insurance' => 'required|integer',
            'employer_insurance_remote' => 'required|integer',
            'personnel_insurance_remote' => 'required|integer',
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'basic_rights' => str_replace(',', '', $this->input('basic_rights')),
            'right_to_housing' => str_replace(',', '', $this->input('right_to_housing')),
            'right_to_marry' => str_replace(',', '', $this->input('right_to_marry')),
            'childrens_right' => str_replace(',', '', $this->input('childrens_right')),
            'right_to_eat_and_drink' => str_replace(',', '', $this->input('right_to_eat_and_drink')),
            'employer_insurance' => str_replace(',', '', $this->input('employer_insurance')),
            'personnel_insurance' => str_replace(',', '', $this->input('personnel_insurance')),
            'employer_insurance_remote' => str_replace(',', '', $this->input('employer_insurance_remote')),
            'personnel_insurance_remote' => str_replace(',', '', $this->input('personnel_insurance_remote')),

        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
