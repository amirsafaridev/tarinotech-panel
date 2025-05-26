<?php

namespace Modules\Project\app\Http\Requests\Admin\Facility;

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
            'base_id' => 'required|exists:project_bases,id',
            'customer_extra_unit' => 'required|numeric',
            'expert_extra_unit' => 'required|numeric',

        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'customer_extra_unit' => str_replace(',', '', $this->input('customer_extra_unit')),
            'expert_extra_unit' => str_replace(',', '', $this->input('expert_extra_unit')),
        ]);
    }
}
