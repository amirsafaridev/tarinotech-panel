<?php

namespace Modules\Survey\app\Http\Requests\Admin\Question;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Survey\app\Enums\Database\QuestionTypeEnum;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_text' => 'required|string|max:500',
            'question_type' => 'required|in:'.implode(',', QuestionTypeEnum::getValues()),
            'is_required' => 'boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'question_text' => 'متن سوال',
            'question_type' => 'نوع سوال',
            'is_required' => 'اجباری بودن',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_required' => $this->has('is_required'),
        ]);
    }
}
