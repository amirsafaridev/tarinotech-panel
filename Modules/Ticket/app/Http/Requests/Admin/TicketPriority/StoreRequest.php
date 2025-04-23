<?php

namespace Modules\Ticket\app\Http\Requests\Admin\TicketPriority;

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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'should_notify' => $this->has('should_notify'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:ticket_priorities,name'],
            'color' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'should_notify' => ['boolean'],
            'level' => ['required', 'integer', 'min:1'],
            'admin_support_id' => ['nullable', 'exists:admins,id'],
            'message' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'نام اولویت',
            'color' => 'رنگ',
            'description' => 'توضیحات',
            'should_notify' => 'اطلاع رسانی',
            'level' => 'سطح اولویت',
            'admin_support_id' => 'پشتیبان',
            'message' => 'پیام اطلاع رسانی',
        ];
    }
}
