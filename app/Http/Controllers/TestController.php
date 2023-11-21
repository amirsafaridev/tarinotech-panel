<?php

namespace App\Http\Controllers;

use Modules\Admin\app\Models\Admin;
use Modules\Admin\app\Notifications\Admin\SendPasswordByEmail;

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
}
