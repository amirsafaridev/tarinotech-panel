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
            'customer_extra_unit' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'expert_extra_unit' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'duration' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],

        ];
    }
   
}
