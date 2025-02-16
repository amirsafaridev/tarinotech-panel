<?php

namespace Modules\Project\app\Http\Requests\Admin\Web;

use App\Enums\Database\Role\RoleName;
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
        $baseRule = [
            'title' => 'required|max:255',
            'domain_primary' => 'required|max:255',

            'user_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:project_types,id',
            'status_id' => 'required|exists:project_statuses,id',

            'price' => 'required|integer',
            'deadline_at' => 'required|jdate',

            'package_id' => 'required|exists:packages,id',
            'pages' => 'required|integer',
            'agreement_at' => 'required|jdate',
            'working_days' => 'required|integer',
            'facilities' => 'array',

            /* Business */
            'business_domain_id' => 'required|exists:business_domains,id',
            'business_domain' => 'string|max:255',

            /* Domain */
            'domain_provider_website' => 'required_if:have_domain,on',
            'domain_username' => 'required_if:have_domain,on',
            'domain_password' => 'required_if:have_domain,on',

            /* Host */
            'host_provider' => 'required_if:have_host,on',
            'host_username' => 'required_if:have_host,on',
            'host_password' => 'required_if:have_host,on',

            /* Language */
            'languages' => 'array',
        ];

        if (hasAdminRole(RoleName::SUPER_ADMIN)) {
            $baseRule['admin_id'] = 'required|exists:admins,id';
        }

        return $baseRule;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'price' => str_replace(',', '', $this->input('price')),
        ]);
    }
}
