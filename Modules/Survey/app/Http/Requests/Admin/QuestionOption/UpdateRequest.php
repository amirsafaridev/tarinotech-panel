<?php

namespace Modules\Survey\app\Http\Requests\Admin\QuestionOption;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'option_text' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:7|regex:/^#[0-9A-F]{6}$/i',
        ];
    }

    public function attributes(): array
    {
        return [
            'option_text' => 'متن گزینه',
            'color_code' => 'رنگ گزینه',
        ];
    }
}
