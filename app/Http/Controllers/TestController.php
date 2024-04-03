<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\app\Notifications\Admin\SendPasswordByEmail;
use Modules\Project\app\Imports\ProjectWebImport;
use Modules\User\app\Imports\UserImport;

class TestController extends Controller
{
    public function index()
    {
        $this->dieInProduction();
        $startDate = '2023-10-01';

        return date('m-d', strtotime($startDate));
    }

    public function sendEmail()
    {
        $this->dieInProduction();
        $admin = Admin::query()->find(14);
        $admin->notify(new SendPasswordByEmail('1234'));
    }

    public function awsUpload()
    {
        $this->dieInProduction();
        // https://docs.arvancloud.ir/fa/object-storage/
        Storage::disk('s3')->put('custom.css', file_get_contents(public_path('res-admin/assets/css/custom.css')));
        $url = Storage::disk('s3')->url('custom5.css');
    }

    public function awsList()
    {
        $this->dieInProduction();
        $s3 = Storage::disk('s3');
        $objects = $s3->get('/custom.css');
    }

    public function import()
    {
        $this->dieInProduction();
        try {
            Excel::import(new UserImport(), 'Customer.xlsx', 'public');
        } catch (ValidationException $e) {
            return $e->failures();
        }
    }

    public function importProject()
    {
        $this->dieInProduction();

        try {
            Excel::import(new ProjectWebImport(), 'WebProject.xlsx', 'public');
        } catch (ValidationException $e) {
            return $e->failures();
        }
    }

    public function phpInfo()
    {
        echo phpinfo();
    }

    private function dieInProduction()
    {
        if (app()->isProduction()) {
            abort(404);
        }
    }
}
