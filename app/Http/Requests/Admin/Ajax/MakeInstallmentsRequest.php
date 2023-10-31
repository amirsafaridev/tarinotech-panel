<?php

namespace App\Http\Requests\Admin\Ajax;

use Illuminate\Foundation\Http\FormRequest;

class MakeInstallmentsRequest extends FormRequest
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
            'price' => 'required|integer|min:1',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => str_replace(',', '', $this->input('price')),
        ]);
    }
}
