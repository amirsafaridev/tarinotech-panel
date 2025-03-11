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
            'date' => 'required|jdate',
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

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
