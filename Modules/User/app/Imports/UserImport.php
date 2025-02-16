<?php

namespace Modules\User\app\Imports;

use App\Helpers\Helper;
use BenSampo\Enum\Rules\EnumValue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Modules\User\app\Enums\PersonType;
use Modules\User\app\Enums\UserType;
use Modules\User\app\Models\UseCellphone;
use Modules\User\app\Models\User;

class UserImport implements OnEachRow, SkipsEmptyRows, ToModel, WithHeadingRow, WithMultipleSheets, WithStartRow, WithValidation
{
    public function model(array $row): User
    {
        $dob = Carbon::parse($row['dob'])->format('Y-m-d');
        if (str($row['dob'])->startsWith('13')) {
            $dob = Helper::toGregorian($row['dob']);
        }

        return new User([
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'en_first_name' => $row['en_first_name'],
            'en_last_name' => $row['en_last_name'],
            'father_name' => $row['father_name'],
            'national_id' => $row['national_id'],
            'document_id' => $row['document_id'],
            'email' => $row['email'],
            'dob' => $dob,
            'person_type' => $row['person_type'],
            'official_bill' => $row['official_bill'],
            'mobile' => $row['mobile'],
            'verify_at' => now(),
            'is_block' => false,
            'user_type' => UserType::Primary,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function sheets(): array
    {
        return [0 => $this];
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'person_type' => ['required', new EnumValue(PersonType::class, false)],
            'official_bill' => 'required',
            //'mobile' => 'required|unique:users,mobile',
            //'email' => 'required|email|unique:users,email',
            'nic_status' => 'required',
            'dob' => 'required',
        ];
    }

    public function customValidationMessages(): array
    {
        return [

        ];
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $user = User::query()
            ->where('mobile', $row['mobile'])
            ->firstOrFail();

        $user->address()->create([
            'address' => $row['address'],
            'postal_code' => $row['postal_code'],
        ]);

        if ($row['company_name'] && $row['company_type']) {
            $user->company()->create([
                'name' => $row['company_name'],
                'identify' => $row['company_identify'],
                'register_id' => $row['company_register_id'],
                'type' => $row['company_type'],
            ]);
        }

        if ($row['nic_status'] == '1') {
            $user->irnic()->create([
                'status' => $row['nic_status'],
                'identify' => $row['nic_identify'] ?? '',
                'password' => $row['nic_password'] ? Crypt::encrypt($row['nic_password']) : '',
            ]);
        }

        $this->createUserCellphones($row['tels'], $user->id);
    }

    private function createUserCellphones(?string $phoneNumbersString, int $userId)
    {
        if (empty($phoneNumbersString)) {
            return;
        }

        $splitPhones = explode('|', $phoneNumbersString);
        $insertable = array_map(function ($phone) use ($userId) {
            return [
                'user_id' => $userId,
                'phone' => $phone,
            ];
        }, $splitPhones);

        UseCellphone::insert($insertable);
    }
}
