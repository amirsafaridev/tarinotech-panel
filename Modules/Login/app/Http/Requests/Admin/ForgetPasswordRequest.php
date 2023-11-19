<?php

namespace Modules\Login\app\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
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
        $rules[] = 'required';
        if (filter_var($this->get('identify'), FILTER_VALIDATE_EMAIL)) {
            $rules[] = 'email';
        } else {
            $rules[] = 'numeric';
        }

        return [
            'identify' => $rules,
            //'captcha' => 'required|captcha',
        ];
    }

    protected function prepareForValidation()
    {

    }

    public function messages(): array
    {
        return [
            'identify.required' => 'فیلد پست الکترونیکی / شماره موبایل را وارد کنید.',
            'identify.email' => 'آدرس پست الکترونیکی را صحیح وارد کنید.',
            'captcha.captcha' => 'کد امینیتی صحیح نیست!',
            'captcha.required' => 'کد امنیتی اجباری است.',
        ];
    }
}
