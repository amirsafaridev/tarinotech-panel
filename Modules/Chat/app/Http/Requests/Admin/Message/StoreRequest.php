<?php

namespace Modules\Chat\app\Http\Requests\Admin\Message;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'message' => 'required',
            'chat_id' => 'required|integer|exists:chats,id',
            'parent_id' => 'nullable|integer',
            'files' => 'nullable|array',
            'files.*' => 'integer',
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'parent_id' => is_numeric($this->input('parent_id')) ? $this->input('parent_id') : null,
        ]);
    }
}
