<?php

namespace Modules\User\app\Http\Requests\Api\User;

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
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'en_first_name' => 'nullable|max:255',
            'en_last_name' => 'nullable|max:255',
            'father_name' => 'nullable|max:255',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([

        ]);
    }
}
