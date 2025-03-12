<?php

namespace Modules\Personnel\app\Http\Requests\Admin\PersonnelAssistance;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'price' => 'required|numeric',
            'description' => 'required',
            'date' => 'required|jdate',

        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'price' => str_replace(',', '', $this->input('price')),
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