<?php

namespace App\Http\Requests\Admin\Project\Web;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
        return [
            'title' => 'required|max:255',
            'domain_primary' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'status_id' => 'required|exists:project_statuses,id',
            'price' => 'required|integer',
            'deadline_at' => 'required|date_format:Y/m/d',

            'field_activity' => 'required|max:255',
            'package_id' => 'required|exists:packages,id',
            'project_type_id' => 'required|exists:project_types,id',
            'pages' => 'required|integer',
            'agreement_at' => 'required|jdate',
            'working_days' => 'required|integer',
            'facilities' => 'array',

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
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => str_replace(',', '', $this->input('price')),
        ]);
    }
}
