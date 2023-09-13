<?php

namespace App\Http\Requests\Admin\AdminPassword;

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
            'password' => 'required|min:6|same:password_rep',
            'password_rep' => 'required|min:6',
        ];
    }
}
