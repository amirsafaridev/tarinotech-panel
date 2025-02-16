<?php

namespace App\Service\Response;

use Illuminate\Support\Facades\Facade;

class ResponseServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ResponseService';
    }
}
