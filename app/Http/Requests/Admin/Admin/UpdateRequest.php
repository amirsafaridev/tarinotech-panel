<?php

namespace App\Http\Requests\Admin\Admin;

use App\Rules\IRMobile;
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
            'mobile' => ['required', new IRMobile()],
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2000',
            'role' => 'required|exists:roles,id',
            'dob' => 'nullable|jdate',
            'start_cooperation' => 'nullable|jdate',
            'start_last_contract' => 'nullable|jdate',
            'end_last_contract' => 'nullable|jdate',
        ];
    }
}
