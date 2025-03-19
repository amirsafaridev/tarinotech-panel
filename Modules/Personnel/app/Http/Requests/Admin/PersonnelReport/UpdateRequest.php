<?php

namespace Modules\Personnel\app\Http\Requests\Admin\PersonnelReport;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:daily,hourly',
            'start_date' => 'required_if:type,daily|date',
            'end_date' => 'required_if:type,daily|date|after_or_equal:start_date',
            'date' => 'required_if:type,hourly|date',
            'start_time' => 'required_if:type,hourly|date_format:H:i',
            'end_time' => 'required_if:type,hourly|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:1000',
            'rules_accepted' => 'required|boolean|accepted',
            'is_emergency' => 'boolean'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function messages(): array
    {
        return [
            'type.required' => 'نوع مرخصی را انتخاب کنید',
            'type.in' => 'نوع مرخصی نامعتبر است',
            'start_date.required_if' => 'تاریخ شروع برای مرخصی روزانه الزامی است',
            'end_date.required_if' => 'تاریخ پایان برای مرخصی روزانه الزامی است',
            'end_date.after_or_equal' => 'تاریخ پایان نمی‌تواند قبل از تاریخ شروع باشد',
            'date.required_if' => 'تاریخ برای مرخصی ساعتی الزامی است',
            'start_time.required_if' => 'ساعت شروع برای مرخصی ساعتی الزامی است',
            'end_time.required_if' => 'ساعت پایان برای مرخصی ساعتی الزامی است',
            'end_time.after' => 'ساعت پایان باید بعد از ساعت شروع باشد',
            'description.max' => 'توضیحات نمی‌تواند بیشتر از 1000 کاراکتر باشد',
            'rules_accepted.required' => 'لطفاً قوانین مرخصی را مطالعه و پذیرش کنید',
            'rules_accepted.accepted' => 'لطفاً قوانین مرخصی را مطالعه و پذیرش کنید'
        ];
    }
}
