<?php

namespace Modules\Chat\app\Http\Requests\Api\Attachment;

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
        logger($this->file('file')->getMimeType());

        return [
            'file' => 'required|file|mimes:jpeg,png,gif,mp4,mov,avi,doc,docx,xls,xlsx,pdf,mp3,wav,ogg|max:10240',
        ];
    }
}
