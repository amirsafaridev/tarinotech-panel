<?php

namespace Modules\Project\app\Http\Requests\Admin\Seo;

use App\Helpers\Helper;
use App\Rules\PriceGreaterThanMinimum;
use BenSampo\Enum\Rules\EnumKey;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Package\app\Models\Package;
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
        $packageId = $this->input('package_id');
        $agreementDate = $this->input('agreement_at');

        // Get the package only if package_id is numeric
        $package = is_numeric($packageId) ? Package::find($packageId) : null;

        // Convert agreement date to Gregorian if it exists
        $date = $agreementDate ? Helper::toGregorian($agreementDate) : null;

        $rules = [
            'title' => 'required|max:255',
            'domain_primary' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'status_id' => 'required|exists:project_statuses,id',
            'package_id' => 'required|integer',
            'due_date_payments' => 'required|integer|min:1',
            'agreement_at' => 'required|jdate',
            'designed_by' => ['required', new EnumValue(ProjectDesignBy::class)],
            'host_location' => ['required', new EnumKey(SeoHostLocation::class)],
            'host_provider' => 'required_if:host_location,'.SeoHostLocation::OUT_COMPANY,
            'keywords' => 'required|array',
        ];

        // Add price validation rule if both package and date are available
        if ($package && $date) {
            $rules['price'] = ['required', 'integer', new PriceGreaterThanMinimum($package, $date)];
        }

        return $rules;
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
