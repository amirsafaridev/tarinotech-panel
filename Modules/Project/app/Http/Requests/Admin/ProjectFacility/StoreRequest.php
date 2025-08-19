<?php

namespace Modules\Project\app\Http\Requests\Admin\ProjectFacility;

use App\Enums\Database\Facility\FinancialCycle;
use App\Enums\Database\Facility\PriceType;
use App\Enums\Database\Facility\WorkCycle;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'facilities' => 'array',
            'user_id' => ['bail', 'required', 'integer', Rule::exists('admins', 'id')],
            'renewal_at' => ['required', 'jdate'],
        ];
    }
}
