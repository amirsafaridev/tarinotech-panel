<?php

namespace Modules\Factor\app\Http\Requests\Admin\Factor;

use App\Rules\IRMobile;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Modules\User\app\Enums\PersonType;

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
            'project_id' => $this->getProjectIdRule(),
            'title' => 'required|max:255',
            'expired_at' => 'required|jdate',
            'item' => 'required|array',
            'item.*.title' => 'required|max:255',
            'item.*.transaction_category_id' => 'required|integer',
            'item.*.price' => 'required|integer',
            'item.*.tax' => 'required|integer',
            'item.*.discount' => 'required|integer',

            /* Custom User Validation Project */
            'project_type_id' => 'required_if:custom_customer,yes|exists:project_types,id',
            'project_status_id' => 'required_if:custom_customer,yes|exists:project_statuses,id',
            'project_package_id' => 'required_if:custom_customer,yes|exists:packages,id',
            'project_title' => 'required_if:custom_customer,yes|max:255',
            'project_price' => 'required_if:custom_customer,yes|integer',

            /* Custom User Validation User */
            'user_first_name' => 'required_if:custom_customer,yes|max:255',
            'user_last_name' => 'required_if:custom_customer,yes|max:255',
            'user_person_type' => ['required_if:custom_customer,yes|max:255', new EnumValue(PersonType::class, false)],
            'user_mobile' => ['required_if:custom_customer,yes', 'max:255', 'unique:users,mobile'/*, new IRMobile()*/],
        ];
    }

    protected function prepareForValidation()
    {
        $items = $this->input('item', []);

        if (count($items)) {
            foreach ($items as &$item) {
                $item['price'] = str_replace(',', '', $item['price']);
                $item['tax'] = str_replace(',', '', $item['tax']);
                $item['discount'] = str_replace(',', '', $item['discount']);
            }
        }

        $this->merge([
            'item' => $items,
            'custom_customer' => $this->input('custom_customer') === 'on' ? 'yes' : 'no',
            'project_id' => $this->input('project_id') !== '' ? $this->input('project_id') : null,
            'project_price' => str_replace(',', '', $this->input('project_price')),
            'person_type' => (int) $this->input('person_type'),
            'user_mobile' => str_replace('+', '', $this->input('user_mobile')),

        ]);
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

            /* Project */
            'project_id.required_if' => 'پروژه الزامی است',

            /* Meta */
            'type_id.required_if' => 'نوع پروژه الزامی است',
            'project_title.required_if' => 'نام پروژه الزامی است',
            'customer_fullname.required_if' => 'نام کارفرما الزارمی است',
            'customer_mobile.required_if' => 'شماره موبایل کارفرما الزامی است',
        ];
    }

    private function getProjectIdRule(): string
    {
        $rule = 'required_if:custom_customer,no';

        if ($this->input('custom_customer') == 'no') {
            $rule .= '|exists:projects,id';
        }

        return $rule;
    }
}
