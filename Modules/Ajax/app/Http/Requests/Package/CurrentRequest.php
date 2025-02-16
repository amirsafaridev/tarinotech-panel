<?php

namespace Modules\Ajax\app\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class CurrentRequest extends FormRequest
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
            'date' => 'required|jdate',
            'package_id' => 'required|integer|exists:packages,id',
        ];
    }
}
