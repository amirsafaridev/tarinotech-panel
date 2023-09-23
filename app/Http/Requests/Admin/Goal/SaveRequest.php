<?php

namespace App\Http\Requests\Admin\Goal;

use Illuminate\Foundation\Http\FormRequest;

class SaveRequest extends FormRequest
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
            'start' => 'required|date_format:Y-m-d',
            'end' => 'required|date_format:Y-m-d',
            'profitability' => 'required|int|min:0',
            'profitability_dollar' => 'required|numeric|min:0',
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'profitability' => str_replace(',', '', $this->input('profitability')),
            'profitability_dollar' => str_replace(',', '', $this->input('profitability_dollar')),
        ]);
    }
}
