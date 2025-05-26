<?php

namespace Modules\Package\app\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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
        $commonRules = [
            'base_id' => ['required'],
            'type_id' => ['required', 'integer', 'exists:project_types,id'],
            'title' => ['required', 'max:255'],
            'price' => ['required', 'numeric'],
            'minimum_price_percent' => ['required', 'numeric', 'between:0,100'],
'main_unit' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],

        ];

        $seoRules = [
            'seo_keywords_count' => ['required', 'numeric'],
            'seo_agreement_duration' => ['required', 'numeric'],
            'seo_amount_content' => ['required', 'numeric'],
        ];

        if ($this->input('base_id') == 2) {
            $rules = array_merge($commonRules, $seoRules);
        } else {
            $rules = $commonRules;
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $fieldsToClean = [
            'price',
            'minimum_price_percent',
            'seo_keywords_count',
            'seo_agreement_duration',
            'seo_amount_content',
        ];

        foreach ($fieldsToClean as $field) {
            $this->merge([
                $field => str_replace(',', '', $this->$field),
            ]);
        }
    }
}
