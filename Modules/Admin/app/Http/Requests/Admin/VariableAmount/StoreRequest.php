<?php

namespace Modules\Admin\app\Http\Requests\Admin\VariableAmount;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'date' => 'required|jdate',
            'job_title_id' => 'required|exists:job_titles,id',
            'title' => 'required',
            'base_units_count' => 'required|numeric',
            'performance_amount' => 'required|numeric',
            'extra_units_amount' => 'required|numeric',
            'reward_basis' => 'required|numeric',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
