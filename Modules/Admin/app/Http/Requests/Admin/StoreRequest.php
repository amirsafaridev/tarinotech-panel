<?php

namespace Modules\Admin\app\Http\Requests\Admin;

use App\Enums\Database\Admin\TypeInsurance;
use App\Enums\Database\Admin\WorkLocation;
use App\Rules\IRMobile;
use App\Rules\ShabaNumber;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

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
            'email' => 'required|unique:admins,email|email',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'job_title' => 'max:255',
            'mobile' => ['required', 'size:11', new IRMobile()],
            'mobile_company' => ['size:11', new IRMobile()],
            'number_company' => 'numeric',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2000',
            'role' => 'required|exists:roles,id',
            'dob' => 'nullable|jdate',
            'start_cooperation' => 'nullable|jdate',
            'start_last_contract' => 'nullable|jdate',
            'end_last_contract' => 'nullable|jdate',

            'tel' => 'size:11|string',
            'postal_code' => 'size:10|string',
            'work_location' => ['required', new EnumValue(WorkLocation::class)],
            'type_insurance' => ['required', new EnumValue(TypeInsurance::class)],
            'promissory' => 'int|min:0',
            'national_code' => 'size:10|string',
            'shaba_number' => ['nullable', 'size:26', new ShabaNumber()],
            'cart_number' => 'size:16|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'work_location' => (int) $this->input('work_location'),
            'type_insurance' => (int) $this->input('type_insurance'),
            'shaba_number' => str_replace(' ', '', $this->input('shaba_number')),
            'cart_number' => str_replace(' ', '', $this->input('cart_number')),
            'promissory' => str_replace(',', '', $this->input('promissory')),
        ]);
    }
}
