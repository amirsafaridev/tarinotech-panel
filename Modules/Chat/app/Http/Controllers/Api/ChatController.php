<?php

namespace Modules\Chat\app\Http\Controllers\Api;

use App\Enums\Database\Chat\ChatType;
use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Modules\Chat\app\Resources\Chat\ChatResource;
use Modules\Support\app\Models\Chat;
use Modules\User\app\Models\User;

class ChatController extends Controller
{
    use HasApiResponse;

    public function index()
    {
        try {
            $chats = Chat::query()
                ->with(['project', 'users' => function ($query) {
                    $query->where('user_type', User::class)
                        ->where('user_id', auth()->id());
                }])
                ->whereHas('users', function ($query) {
                    $query->where('user_type', User::class)
                        ->where('user_id', auth()->id());
                })
                ->orWhere('type', ChatType::Public)
                ->latest()
                ->get();

            $data = ChatResource::collection($chats);

            return $this->successResponse($data, 'chat list');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
