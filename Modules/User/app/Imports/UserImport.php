<?php

namespace Modules\User\app\Imports;

use App\Helpers\Helper;
use App\Rules\IRMobile;
use BenSampo\Enum\Rules\EnumValue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\User\app\Enums\PersonType;
use Modules\User\app\Enums\UserType;
use Modules\User\app\Models\User;

class UserImport implements ToModel, WithMultipleSheets, WithStartRow, WithValidation
{
    public function model(array $row): User
    {
        $dob = $row[9] ? Helper::toGregorian($row[9]) : null;

        return new User([
            'first_name' => $row[0],
            'last_name' => $row[1],
            'en_first_name' => $row[2],
            'en_last_name' => $row[3],
            'father_name' => $row[4],
            'national_id' => $row[5],
            'document_id' => $row[6],
            'tel' => $row[7],
            'email' => $row[8],
            'dob' => $dob,
            'person_type' => $row[10],
            'official_bill' => $row[11],
            'mobile' => $row[12],
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
            '*.12' => ['required', 'unique:users,mobile', new IRMobile()],
            '*.0' => 'required|max:255',
            '*.1' => 'required|max:255',
            '*.10' => ['required', new EnumValue(PersonType::class, false)],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.12.require' => 'موبایل اجباری است',
            '*.12.unique' => 'شماره همراه تکراری می باشد.',
            '*.0.required' => 'فیلد نام اجباری است.',
            '*.1.required' => 'فیلد نام خانوادگی اجباری است.',
        ];
    }
}
