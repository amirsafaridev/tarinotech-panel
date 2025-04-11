<?php

namespace Modules\Ticket\app\Http\Requests\Admin\TicketStatusTransition;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'from_status_id' => ['required', 'exists:ticket_statuses,id'],
            'to_status_id' => [
                'required',
                'exists:ticket_statuses,id',
                Rule::notIn([$this->from_status_id]),
                function ($attribute, $value, $fail) {
                    if ($value == $this->from_status_id) {
                        $fail('وضعیت شروع و پایان نمی‌توانند یکسان باشند.');
                    }
                },
            ],
            'days_until_transition' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
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
            'from_status_id' => 'وضعیت شروع',
            'to_status_id' => 'وضعیت پایان',
            'days_until_transition' => 'تعداد روز تا انتقال',
            'is_active' => 'فعال',
        ];
    }
}
