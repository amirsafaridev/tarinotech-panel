<?php

namespace App\Http\Requests\Admin\Project\Seo;

use App\Enums\Database\Project\ProjectDesignBy;
use App\Enums\Database\Project\SeoAgreementDuration;
use App\Enums\Database\Project\SeoHostLocation;
use BenSampo\Enum\Rules\EnumKey;
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
            'title' => 'required|max:255',
            'domain_primary' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'status_id' => 'required|exists:project_statuses,id',
            'price' => 'required|integer',
            'price_monthly' => 'required|integer',
            'due_date_payments' => 'required|integer|min:1',
            'agreement_at' => 'required|jdate',
            'agreement_duration' => ['required', new EnumValue(SeoAgreementDuration::class)],
            'designed_by' => ['required', new EnumValue(ProjectDesignBy::class)],
            /* Host */
            'host_location' => ['required', new EnumKey(SeoHostLocation::class)],
            'host_provider' => 'required_if:host_location,'.SeoHostLocation::OUT_COMPANY,

            'amount_content' => 'required',
            'keywords_count' => 'required|numeric|min:1',
            'keywords' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => str_replace(',', '', $this->input('price')),
            'price_monthly' => str_replace(',', '', $this->input('price_monthly')),
            'agreement_duration' => (int) $this->input('agreement_duration'),
            'designed_by' => (int) $this->input('designed_by'),
        ]);
    }

    public function messages(): array
    {
        return [
            'host_provider.required_if' => 'فیلد هاستینگ پرووایدر الزامی است.',
        ];
    }
}
