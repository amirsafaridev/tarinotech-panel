<?php

namespace App\Http\Controllers;

use Modules\Chat\app\Events\Message\PrivateTest;
use Modules\Chat\app\Events\Message\PublicTest;

class SocketTestController extends Controller
{
    public function sendPrivate()
    {
        broadcast(new PrivateTest());
    }

    public function sendPublic()
    {
        broadcast(new PublicTest());
    }
}
