<?php

namespace Modules\Contract\app\Http\Requests\Admin\Signable;

use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Contract\app\Enums\SignableStatus;

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
            'status' => ['required', new EnumValue(SignableStatus::class)],
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'status' => (int) $this->input('status'),
        ]);
    }
}
