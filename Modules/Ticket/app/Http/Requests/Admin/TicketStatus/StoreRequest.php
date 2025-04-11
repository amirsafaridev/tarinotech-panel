<?php

namespace Modules\Ticket\app\Http\Requests\Admin\TicketStatus;

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
            'name' => ['required', 'string', 'max:255', 'unique:ticket_statuses,name'],
            'color' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_auto_changing' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
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
            'name' => 'نام وضعیت',
            'color' => 'رنگ',
            'description' => 'توضیحات',
            'is_auto_changing' => 'تغییر خودکار',
            'order' => 'ترتیب',
        ];
    }
}
