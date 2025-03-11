<?php

namespace Modules\Admin\app\Http\Requests\Admin\PersonnelAssistance;

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
            'user_id' => 'required|exists:admins,id',
            'date' => 'required|jdate',

        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}