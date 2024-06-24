<?php

namespace Modules\Chat\app\Http\Controllers\Api;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Chat\app\Http\Requests\Api\Chat\CloseRequest;
use Modules\Chat\app\Resources\Chat\ChatResource;
use Modules\Support\app\Models\Chat;
use Modules\User\app\Models\User;
use Symfony\Component\HttpFoundation\Response as HttpResponseCode;

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
                ->whereNot('type', ChatType::Ticket)
                ->orWhere('type', ChatType::Public)

                ->orderByRaw('CASE WHEN type = '.ChatType::Public.' THEN 0 ELSE 1 END')
                ->orderBy('created_at', 'desc')
                ->get();

            $data = ChatResource::collection($chats);

            return $this->successResponse($data, 'chat list');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function close(CloseRequest $request)
    {
        try {

            $chat = Chat::query()
                ->whereHas('users', function ($query) {
                    $query->where('user_type', User::class)
                        ->where('user_id', auth()->id());
                })
                ->findOrFail($request->input('chat_id'));

            if ($chat->type !== ChatType::Ticket || $chat->status !== ChatStatus::Close) {
                return $this->failResponse('اطلاعات ارسال شده صحیح نیست!', HttpResponseCode::HTTP_CONFLICT);
            }

            if ($chat->meta) {
                return $this->failResponse('شما قبلا نظر خود را ثبت کرده اید!', HttpResponseCode::HTTP_CONFLICT);
            }

            $chat->meta()->create([
                'rate' => $request->input('rate'),
            ]);

            return $this->successResponse(null, 'نظر شما با موفقیت ثبت شد.');

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }
}
