<?php

namespace Modules\Content\app\Http\Requests\Admin\Slider;

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
            'description' => 'required',
            'photo' => 'mimes:jpg,png,webp|max:1024',
            'published_at' => 'required|jdate',
            'archived_at' => 'required|jdate',
        ];
    }
}
