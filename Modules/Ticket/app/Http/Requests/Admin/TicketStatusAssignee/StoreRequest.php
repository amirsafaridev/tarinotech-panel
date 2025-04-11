<?php

namespace Modules\Ticket\app\Http\Requests\Admin\TicketStatusAssignee;

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
            'status_id' => [
                'required',
                'exists:ticket_statuses,id',
                Rule::unique('ticket_status_assignees', 'status_id'),
            ],
            'role_id' => ['required', 'exists:roles,id'],
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
            'status_id' => 'وضعیت تیکت',
            'role_id' => 'نقش',
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status_id.unique' => 'این وضعیت تیکت قبلاً به یک نقش اختصاص داده شده است.',
        ];
    }
}
