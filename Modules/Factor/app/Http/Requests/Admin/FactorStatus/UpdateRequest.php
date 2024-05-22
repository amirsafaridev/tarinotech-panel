<?php

namespace Modules\Factor\app\Http\Requests\Admin\FactorStatus;

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
        return [
            'factor_status' => ['required'],
            'project_status_id' => 'required|exists:project_statuses,id',
            'project_status_forward_id' => 'required|exists:project_statuses,id',
        ];
    }
}
