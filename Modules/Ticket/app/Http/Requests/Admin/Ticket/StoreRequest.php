<?php

namespace Modules\Ticket\app\Http\Requests\Admin\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'subject_id' => 'required|exists:ticket_subjects,id',
            'status_id' => 'required|exists:ticket_statuses,id',
            'priority_id' => 'required|exists:ticket_priorities,id',
            'assigned_to' => 'nullable|exists:admins,id',
            'initial_message' => 'required|string|min:5',
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
            'title' => 'عنوان تیکت',
            'user_id' => 'کاربر',
            'project_id' => 'پروژه',
            'subject_id' => 'موضوع تیکت',
            'status_id' => 'وضعیت تیکت',
            'priority_id' => 'اولویت تیکت',
            'assigned_to' => 'پشتیبان',
            'initial_message' => 'پیام اولیه',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
