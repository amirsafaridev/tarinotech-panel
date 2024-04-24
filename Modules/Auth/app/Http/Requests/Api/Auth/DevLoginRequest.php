<?php

namespace Modules\Auth\app\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class DevLoginRequest extends FormRequest
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
            'mobile' => 'required',
        ];
    }

    protected function prepareForValidation()
    {

    }
}
