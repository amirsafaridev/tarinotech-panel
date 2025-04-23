<?php

namespace Modules\Ticket\app\Http\Requests\Admin\TicketTransition;

use Illuminate\Contracts\Validation\ValidationRule;
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
            'is_active' => $this->has('is_active'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_status_id' => ['required', 'exists:ticket_statuses,id'],
            'to_status_id' => ['required', 'exists:ticket_statuses,id', 'different:from_status_id'],
            'event_id' => ['required', 'exists:ticket_events,id'],
            'days_trigger' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
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
            'from_status_id' => 'وضعیت مبدا',
            'to_status_id' => 'وضعیت مقصد',
            'event_id' => 'رویداد',
            'days_trigger' => 'تعداد روز',
            'is_active' => 'فعال',
        ];
    }
}
