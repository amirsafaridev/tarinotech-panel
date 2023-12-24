<?php

namespace Modules\Support\app\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'project_id' => 'required|integer|exists:projects,id',
            'admin_id' => 'required|array',
            'logo' => 'nullable|image|mimes:img,png,jpeg|max:5024',
        ];
    }
}
