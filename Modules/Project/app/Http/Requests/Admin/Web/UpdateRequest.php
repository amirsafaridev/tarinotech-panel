<?php

namespace Modules\Project\app\Http\Requests\Admin\Web;

use App\Enums\Database\Role\PermissionName;
use App\Enums\Database\Role\RoleName;
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
     *
     * @return array
     */
    public function rules()
    {
        $baseRule = [
            'title' => 'required|max:255',
            'domain_primary' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'deadline_at' => 'required|date_format:Y/m/d',

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

        if (hasAdminPermission(PermissionName::PROJECT_PRICE_EDIT)) {
            $baseRule['price'] = 'required|integer';
        }

        return $baseRule;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => str_replace(',', '', $this->input('price', '')),
        ]);
    }
}
