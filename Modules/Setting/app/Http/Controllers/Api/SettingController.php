<?php

namespace Modules\Setting\app\Http\Controllers\Api;

use App\Enums\Database\Chat\AttachmentType;
use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use App\Enums\Database\Company\CompanyType;
use App\Enums\Database\Setting\SettingItems;
use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Setting\app\Models\Setting;
use Modules\User\app\Enums\PersonType;
use Modules\User\app\Enums\UserType;

class SettingController extends Controller
{
    use HasApiResponse;

    public function index()
    {
        try {
            $settings = Setting::query()
                ->whereIn('key', SettingItems::asArray())
                ->get()
                ->pluck('value', 'key')
                ->except(
                    [
                        'target_year',
                        'direct_confirm_project',
                        'direct_confirm_factor',
                    ]
                );

            return $this->successResponse($settings, 'settings');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function enums()
    {
        try {

            $enums['user_type'] = UserType::asApi();
            $enums['person_type'] = PersonType::asApi();
            $enums['chat_status'] = ChatStatus::asApi();
            $enums['chat_type'] = ChatType::asApi();
            $enums['attachment_type'] = AttachmentType::asApi();
            $enums['company_type'] = CompanyType::asApi();
            $enums['factor_status'] = FactorStatus::asApi();

            return $this->successResponse($enums, 'enums list');

        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
