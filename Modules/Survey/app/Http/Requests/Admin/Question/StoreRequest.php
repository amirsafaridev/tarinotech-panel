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
        $rules = [
            'question_text' => 'required|string|max:500',
            'question_type' => 'required|in:'.implode(',', QuestionTypeEnum::getValues()),
            'is_required' => 'boolean',
        ];

        if ($this->input('question_type') == QuestionTypeEnum::Number) {
            $rules['settings.min'] = 'nullable|numeric';
            $rules['settings.max'] = 'nullable|numeric|gte:settings.min';
            $rules['settings.step'] = 'nullable|numeric|min:0.000001';
            $rules['settings.default'] = 'nullable|numeric|gte:settings.min|lte:settings.max';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'question_text' => 'متن سوال',
            'question_type' => 'نوع سوال',
            'is_required' => 'اجباری بودن',
            'settings.min' => 'حداقل مقدار',
            'settings.max' => 'حداکثر مقدار',
            'settings.step' => 'گام',
            'settings.default' => 'مقدار پیش‌فرض',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_required' => $this->has('is_required'),
        ]);
    }
}
