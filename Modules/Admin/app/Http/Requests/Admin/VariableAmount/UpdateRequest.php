<?php

namespace Modules\Admin\app\Http\Requests\Admin\VariableAmount;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'date' => 'required',
            'job_title_id' => 'required|exists:job_titles,id',
            'base_units_count' => 'required|numeric',
            'performance_amount' => 'required|numeric',
            'extra_units_amount' => 'required|numeric',
            'reward_basis' => 'required|numeric',
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'base_units_count' => str_replace(',', '', $this->input('base_units_count')),
            'performance_amount' => str_replace(',', '', $this->input('performance_amount')),
            'extra_units_amount' => str_replace(',', '', $this->input('extra_units_amount')),
            'reward_basis' => str_replace(',', '', $this->input('reward_basis')),
        

        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
