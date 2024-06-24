<?php

namespace Modules\Chat\app\Http\Requests\Api\Chat;

use Illuminate\Foundation\Http\FormRequest;

class CloseRequest extends FormRequest
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
            'chat_id' => 'required|integer',
            'rate' => 'required|integer|min:1|max:5',
        ];
    }
}
