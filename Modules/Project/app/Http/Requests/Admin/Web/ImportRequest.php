<?php

namespace Modules\Project\app\Http\Requests\Admin\Web;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
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
        $allowedMimeTypes = [
            'application/vnd.ms-excel', // For .xls files
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // For .xlsx files
        ];

        return [
            'file' => 'required|mimetypes:'.implode(',', $allowedMimeTypes),
        ];
    }
}
