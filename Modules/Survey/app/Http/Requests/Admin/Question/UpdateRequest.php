<?php

namespace Modules\Survey\app\Http\Requests\Admin\Question;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'question_text' => 'required|string|max:500',
            'question_type' => 'required|in:text,single_choice,multiple_choice,rating,scale',
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

        return $rules;
    }

    public function attributes()
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
