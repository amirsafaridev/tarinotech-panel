<?php

namespace App\Http\Requests\Admin\Project\Web;

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
            'deadline_at' => 'required|date_format:Y-m-d',

            'field_activity' => 'required|max:255',
            'package_id' => 'required|exists:packages,id',
            'project_type_id' => 'required|exists:project_types,id',
            'pages' => 'required|integer',
            'agreement_at' => 'required|jdate',
            'working_days' => 'required|integer',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => str_replace(',', '', $this->input('price')),
        ]);
    }
}
