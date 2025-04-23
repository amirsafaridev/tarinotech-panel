<?php

namespace App\Http\ViewComposers\Admin\User;

use Illuminate\Contracts\View\View;

class UserComposer
{
    public function __construct()
    {

    }

    public function compose(View $view)
    {

        $tests = [];

        $view->with(compact('tests'));
    }
}
