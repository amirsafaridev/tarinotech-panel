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
            'should_notify' => ['nullable', 'boolean'],
            'level' => ['required', 'integer', 'min:1'],
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
        ];
    }
}
