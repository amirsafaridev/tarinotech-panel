<?php

namespace Modules\Auth\App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
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
        return ['code' => 'required'];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'کد تایید را وارد کنید.',
        ];
    }
}
