<?php

namespace App\Http\Controllers;

use Modules\Admin\app\Models\Admin;
use Modules\Admin\app\Notifications\Admin\SendPasswordByEmail;
use Storage;

class TestController extends Controller
{
    public function index()
    {
        $startDate = '2023-10-01';

        return date('m-d', strtotime($startDate));
    }

    public function sendEmail()
    {
        $admin = Admin::query()->find(14);
        $admin->notify(new SendPasswordByEmail('1234'));
    }

    public function awsUpload()
    {
        // https://docs.arvancloud.ir/fa/object-storage/
        Storage::disk('s3')->put('custom.css', file_get_contents(public_path('res-admin/assets/css/custom.css')));
        $url = Storage::disk('s3')->url('custom5.css');
        dd($url);
    }

    public function awsList()
    {
        $s3 = Storage::disk('s3');

        // List objects in the S3 bucket
        $objects = $s3->get('/custom.css');

        dd($objects);
    }
}
