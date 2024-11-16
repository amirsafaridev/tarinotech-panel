<?php

namespace Modules\Admin\app\Http\Requests\Admin\Profile;

use App\Enums\Database\Admin\OtpSendWay;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:255',
            'otp_send_way' => ['required', new EnumValue(OtpSendWay::class)],
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'otp_send_way' => (int) $this->input('otp_send_way'),
        ]);
    }
}
