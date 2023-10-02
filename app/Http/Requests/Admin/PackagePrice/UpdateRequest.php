<?php

namespace App\Http\Requests\Admin\PackagePrice;

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
            'price' => 'required|numeric',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => str_replace(',', '', $this->input('price')),
        ]);
    }
}
