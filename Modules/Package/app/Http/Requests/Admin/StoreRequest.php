<?php

namespace Modules\Package\app\Http\Requests\Admin;

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
            'base_id' => 'required|exists:project_bases,id',
            'title' => 'required|max:255',
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
