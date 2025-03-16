<?php

namespace Modules\Survey\app\Http\Requests\Admin\Survey;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Survey\app\Enums\Database\AuthTypeEnum;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requires_auth' => 'boolean',
            'auth_guard' => ['nullable', 'required_if:requires_auth,true', 'in:'.implode(',', AuthTypeEnum::getValues())],
            'is_active' => 'boolean',
            'start_date' => 'nullable|date_format:Y/m/d',
            'end_date' => 'nullable|date_format:Y/m/d|after_or_equal:start_date',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'عنوان نظرسنجی',
            'description' => 'توضیحات',
            'requires_auth' => 'نیاز به احراز هویت',
            'auth_guard' => 'گارد احراز هویت',
            'is_active' => 'وضعیت',
            'start_date' => 'تاریخ شروع',
            'end_date' => 'تاریخ پایان',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'auth_guard' => $this->has('requires_auth') && $this->input('auth_guard') ? (int) $this->input('auth_guard') : null,
            'is_active' => $this->has('is_active'),
            'requires_auth' => $this->has('requires_auth'),
        ]);
    }
}
