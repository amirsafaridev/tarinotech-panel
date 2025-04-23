<?php

namespace Modules\Ticket\app\Http\Requests\Admin\TicketSubject;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateRequest extends StoreRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['title'] = ['required', 'string', 'max:255', 'unique:ticket_subjects,title,'.$this->ticketSubject->id];

        return $rules;
    }
}
