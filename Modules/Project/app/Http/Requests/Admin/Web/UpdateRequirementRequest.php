<?php

namespace Modules\Project\app\Http\Requests\Admin\Web;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequirementRequest extends FormRequest
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
            'representative' => 'required|string|max:255',
            'color_scheme' => 'required|string|max:255',
            'similar_websites' => 'required|string',
            'preferred_websites' => 'required|string',
            'site_title' => 'required|string|max:255',
            'design_based_on' => 'required|string|max:255',
            'menu_titles' => 'required|string',
            'homepage_layout' => 'required|string',
            'website_features' => 'required|string',
            'internal_pages_content.*.page' => 'required|string',
            'internal_pages_content.*.content' => 'required|string',
            'contract_differences.*.content' => 'required|string',
            'final_decision' => 'required|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'final_decision' => (bool) $this->input('final_decision'),
        ]);
    }
}
