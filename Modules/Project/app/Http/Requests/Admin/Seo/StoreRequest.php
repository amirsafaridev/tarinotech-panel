<?php

namespace Modules\Project\app\Http\Requests\Admin\Seo;

use BenSampo\Enum\Rules\EnumKey;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Project\app\Enums\ProjectDesignBy;
use Modules\Project\app\Enums\SeoHostLocation;

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
            'package_id' => 'required|exists:packages,id',

            'price' => 'required|integer',
            'due_date_payments' => 'required|integer|min:1',
            'agreement_at' => 'required|jdate',
            'designed_by' => ['required', new EnumValue(ProjectDesignBy::class)],

            /* Host */
            'host_location' => ['required', new EnumKey(SeoHostLocation::class)],
            'host_provider' => 'required_if:host_location,'.SeoHostLocation::OUT_COMPANY,

            'keywords' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'host_provider.required_if' => 'فیلد هاستینگ پرووایدر الزامی است.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => str_replace(',', '', $this->input('price')),
            'designed_by' => (int) $this->input('designed_by'),
        ]);
    }
}
