<?php

namespace Modules\Auth\App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'password' => 'required|same:password_rep|min:8',
            'code' => 'required',
            'captcha' => 'required|captcha',
        ];
    }

    protected function prepareForValidation()
    {

    }
}
