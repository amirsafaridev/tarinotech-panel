<?php

namespace Modules\User\app\Imports;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Modules\User\app\Enums\UserType;
use Modules\User\app\Models\User;

class UserImport implements OnEachRow, SkipsEmptyRows, ToModel, WithMultipleSheets, WithStartRow, WithValidation
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
            '0' => 'required',
            '1' => 'required',
            '10' => 'required',
            '11' => 'required',
            '12' => 'required',
            '15' => 'required',
            '18' => 'required',
            '19' => 'required',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '12.require' => 'موبایل اجباری است',
            '12.unique' => 'شماره همراه تکراری می باشد.',
            '0.required' => 'فیلد نام اجباری است.',
            '1.required' => 'فیلد نام خانوادگی اجباری است.',
        ];
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $user = User::query()
            ->where('mobile', $row[12])
            ->firstOrFail();

        $user->address()->create([
            'address' => $row[13],
            'postal_code' => $row[14],
        ]);

        $user->company()->create([
            'name' => $row[15],
            'identify' => $row[16],
            'register_id' => $row[17],
            'type' => $row[18],
        ]);

        if ($row[19] == '1') {
            $user->irnic()->create([
                'status' => $row[19],
                'identify' => $row[20],
                'password' => $row[21] ? Crypt::encrypt($row[21]) : '',
            ]);
        }
    }
}
