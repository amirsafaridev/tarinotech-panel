<?php

namespace Modules\User\app\Http\Requests\Admin\Presenter;

use App\Rules\IRMobile;
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
            'mobile' => ['required', 'unique:users,mobile', new IRMobile()],
            'email' => 'required|max:255',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'project_ids' => 'required|array',
            'project_ids.*' => 'required|integer',
        ];
    }

    protected function prepareForValidation()
    {

    }
}
