<?php

namespace App\Http\Controllers;

use App\Helper\Helper;

class HomeController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        echo Helper::toGregorian('1402-06-25');
        echo asset('');
    }
}
