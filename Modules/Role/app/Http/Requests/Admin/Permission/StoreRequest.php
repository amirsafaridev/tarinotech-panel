<?php

namespace Modules\Role\app\Http\Requests\Admin\Permission;

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
            'name' => 'required|max:255|alpha_dash|unique:permissions,name',
            'title' => 'required|max:255',
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'name' => str($this->input('name'))->upper()->toString(),
        ]);
    }
}
