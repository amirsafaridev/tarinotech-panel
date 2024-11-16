<?php

namespace App\Http\Controllers;

class ErrorPageController extends Controller
{
    public function blockedIp()
    {
        return view('errors.blocked');
    }
}
