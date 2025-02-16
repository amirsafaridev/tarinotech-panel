<?php

namespace Modules\Support\app\Http\Controllers\Admin;

class SupportController
{
    const INDEX_TITLE = 'پشتیبانی ها';

    public function index()
    {
        $title = self::INDEX_TITLE;

        return view('support::admin.index', compact('title'));

    }
}
