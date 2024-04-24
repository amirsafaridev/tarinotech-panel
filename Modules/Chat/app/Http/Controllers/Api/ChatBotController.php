<?php

namespace Modules\Chat\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;

class ChatBotController extends Controller
{
    use HasApiResponse;

    public function rate()
    {
        try {
            return $this->successResponse([], 'chat list');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
