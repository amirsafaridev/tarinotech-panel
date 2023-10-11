<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function index()
    {
        $startDate = '2023-10-01';

        return date('m-d', strtotime($startDate));
    }
}
