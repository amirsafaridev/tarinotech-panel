<?php

namespace Modules\Project\app\Http\Requests\Admin\Ads;

use App\Enums\Database\Role\RoleName;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Project\app\Enums\ProjectDesignBy;

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
        $baseRule = [
            'title' => 'required|max:255',
            'domain_primary' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'status_id' => 'required|exists:project_statuses,id',
            'agreement_at' => 'required|jdate',
            'designed_by' => ['required', new EnumValue(ProjectDesignBy::class)],
        ];

        if (hasAdminRole(RoleName::SUPER_ADMIN)) {
            $baseRule['admin_id'] = 'required|exists:admins,id';
        }

        return $baseRule;
    }

    public function messages(): array
    {
        return [
            '' => '',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'designed_by' => (int) $this->input('designed_by'),
        ]);
    }
}
