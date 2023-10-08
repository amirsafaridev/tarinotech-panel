<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;

class TestController extends Controller
{
    public function index()
    {
        BlogCategory::query()->create([
            'title' => 'بلاگ تست',
        ]);
    }
}
