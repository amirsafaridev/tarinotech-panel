<?php

namespace Modules\Project\app\Http\Requests\Admin\Ads;

use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Project\app\Enums\ProjectDesignBy;

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
            'domain_primary' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'status_id' => 'required|exists:project_statuses,id',
            'agreement_at' => 'required|jdate',
            'designed_by' => ['required', new EnumValue(ProjectDesignBy::class)],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'designed_by' => (int) $this->input('designed_by'),
        ]);
    }

    public function messages(): array
    {
        return [
            '' => '',
        ];
    }
}
