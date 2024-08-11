<?php

namespace Modules\Contract\app\Http\Requests\Admin\UserSignable;

use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Contract\app\Enums\UserSignableStatus;

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
            'status' => ['required', new EnumValue(UserSignableStatus::class)],
            'attachments' => 'nullable|array',
            'attachments.*' => 'ulid',
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'status' => (int) $this->input('status'),
        ]);
    }
}
