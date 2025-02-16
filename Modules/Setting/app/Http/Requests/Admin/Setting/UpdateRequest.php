<?php

namespace Modules\Setting\app\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('ADMIN_SETTING_UPDATE');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'meta_title' => 'required',
            'meta_description' => 'required',
            'target_year' => 'required|numeric|min:1',
        ];
    }
}
