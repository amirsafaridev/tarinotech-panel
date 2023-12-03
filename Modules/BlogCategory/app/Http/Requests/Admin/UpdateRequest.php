<?php

namespace Modules\BlogCategory\app\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'title' => 'required|max:255',
            'slug' => 'required|unique:blog_categories,slug,'.$this->input('id'),
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => generatePersianSlug($this->input('slug')),
        ]);
    }
}
