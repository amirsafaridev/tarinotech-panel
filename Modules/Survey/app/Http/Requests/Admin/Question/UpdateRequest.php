<?php

namespace Modules\Survey\app\Http\Requests\Admin\Question;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Survey\app\Enums\Database\QuestionTypeEnum;

class UpdateRequest extends FormRequest
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

        if ($this->input('question_type') === 'rating') {
            $rules['settings.min'] = 'required|integer|min:1|max:10';
            $rules['settings.max'] = 'required|integer|min:2|max:10|gt:settings.min';
            $rules['settings.step'] = 'required|numeric|min:0.1|max:1';
        }

        if ($this->input('question_type') === 'scale') {
            $rules['settings.min'] = 'required|integer|min:0|max:100';
            $rules['settings.max'] = 'required|integer|min:1|max:100|gt:settings.min';
            $rules['settings.step'] = 'required|numeric|min:0.1|max:10';
            $rules['settings.min_label'] = 'required|string|max:50';
            $rules['settings.max_label'] = 'required|string|max:50';
        }

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
            'settings.min_label' => 'برچسب حداقل',
            'settings.max_label' => 'برچسب حداکثر',
        ];
    }
}
