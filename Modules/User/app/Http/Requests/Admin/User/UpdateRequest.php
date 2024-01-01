<?php

namespace Modules\User\app\Http\Requests\Admin\User;

use App\Enums\Database\Company\CompanyType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Modules\User\app\Enums\IrnicStatus;
use Modules\User\app\Enums\PersonType;

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
        $rules = [
            'tel' => 'required|max:255',
            'email' => 'required|max:255',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'en_first_name' => 'required|max:255',
            'en_last_name' => 'required|max:255',
            'father_name' => 'required|max:255',
            'national_id' => 'required|max:255',
            'document_id' => 'required|max:255',
            'dob' => 'required|jdate',
            'person_type' => ['required', new EnumValue(PersonType::class, false)],
            'address' => 'required',
            'postal_code' => 'required|max:255',
            'irnic_status' => ['required', new EnumValue(IrnicStatus::class, false)],
            'avatar' => 'nullable|mimes:jpg,png,jpeg|max:5024',
            'national_photo' => 'nullable|mimes:jpg,png,jpeg|max:5024',
        ];
        if ($this->input('person_type') === PersonType::Legal) {
            $rules = [
                'company_name' => 'required|max:255',
                'company_identify' => 'max:255',
                'company_register_id' => 'max:255',
                'company_type' => [new EnumValue(CompanyType::class, false)],
            ];
        }

        if ($this->input('irnic_status') === IrnicStatus::HasIt) {
            $rules = [
                'irnic_identify' => 'required|max:255',
                'irnic_password' => 'required|max:255',
            ];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'irnic_status' => (int) $this->input('irnic_status'),
            'person_type' => (int) $this->input('person_type'),
            'company_type' => (int) $this->input('company_type'),
        ]);
    }
}
