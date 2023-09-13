<?php

namespace App\Http\Requests\Admin\Profile;

use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => 'required|min:8|email',
            'new_password' => 'required|same:new_password_rep|min:8',
            'new_password_rep' => 'required|min:8',
        ];
    }
}
