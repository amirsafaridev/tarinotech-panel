<?php

namespace Modules\Blog\app\Http\Requests\Admin\Blog;

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
            'title' => 'required|max:255',
            'body' => 'required',
            'blog_category_id' => 'required|integer|exists:blog_categories,id',
            'photo' => 'nullable|image|mimes:img,png,jpeg|max:5024',
        ];
    }
}
