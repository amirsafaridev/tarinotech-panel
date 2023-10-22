<?php

namespace App\Http\Requests\Admin\Factor;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'project_id' => 'required|integer|exists:projects,id',
            'title' => 'required|max:255',
            'expired_at' => 'required|jdate',
            'item' => 'required|array',
            'item.*.title' => 'required|max:255',
            'item.*.transaction_category_id' => 'required|integer',
            'item.*.price' => 'required|integer',
            'item.*.tax' => 'required|integer',
            'item.*.discount' => 'required|integer',
        ];
    }

    protected function prepareForValidation()
    {
        $items = $this->input('item');

        foreach ($items as &$item) {
            $item['price'] = str_replace(',', '', $item['price']);
            $item['tax'] = str_replace(',', '', $item['tax']);
            $item['discount'] = str_replace(',', '', $item['discount']);
        }

        $this->merge(['item' => $items]);
    }

    public function messages(): array
    {
        return [
            'item.required' => 'ایتم های فاکتور اضافه نشده است.',
            'item.*.title.required' => 'ایتم فاکتور عنوان الزامی است.',
            'item.*.transaction_category_id.required' => 'ایتم فاکتور نوع واریزی الزامی است.',
            'item.*.price.required' => 'ایتم فاکتور مبلغ الزامی است.',
            'item.*.tax.required' => 'ایتم فاکتور مالیات الزامی است.',
            'item.*.discount.required' => 'ایتم فاکتور تخفیف الزامی است.',
        ];
    }
}
