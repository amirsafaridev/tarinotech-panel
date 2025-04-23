<?php

namespace Modules\Ticket\app\Http\Requests\Admin\TicketPriority;

use Illuminate\Validation\Rule;

class UpdateRequest extends StoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['name'] = [
            'required',
            'string',
            'max:255',
            Rule::unique('ticket_priorities', 'name')->ignore($this->ticketPriority),
        ];

        return $rules;
    }
}
