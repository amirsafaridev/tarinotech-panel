<?php

namespace Modules\Chat\app\Http\Controllers\Admin;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Modules\Chat\app\Events\Message\NewMessage;
use Modules\Chat\app\Http\Requests\Admin\Chat\CloseRequest;
use Modules\Support\app\Models\Chat;
use Modules\Support\app\Models\ChatBot;
use Symfony\Component\HttpFoundation\Response as HttpResponseCode;
use View;

class ChatController extends Controller
{
    use HasJsonCommonResponse;

    public function close(CloseRequest $request)
    {
        try {
            DB::beginTransaction();
            $chat = Chat::query()
                ->findOrFail($request->input('chat_id'));

            if ($chat->type !== ChatType::Ticket) {
                return $this->failure('اطلاعات ارسال شده صحیح نیست!', HttpResponseCode::HTTP_CONFLICT);
            }

            if ($chat->status === ChatStatus::Close) {
                return $this->failure('این گفتگو قبلا بسته شده است!', HttpResponseCode::HTTP_CONFLICT);
            }

            $chat->update([
                'status' => ChatStatus::Close,
            ]);

            $content = 'از اینکه از خدمات پشتیبانی ما استفاده کردید، متشکریم. لطفاً به ما کمک کنید تا خدماتمان را بهتر کنیم. اگر ممکن است، به این پشتیبانی از ۱ تا ۵ امتیاز بدهید و نظرتان را با ما به اشتراک بگذارید.';

            $message = $chat->messages()->create([
                'user_type' => ChatBot::class,
                'user_id' => 2,
                'content' => $content,
            ]);

            DB::commit();

            $htmlRender = compressHtml(View::make('support::admin.part.row-message', ['message' => $message, 'reverse' => true]));

            broadcast(new NewMessage($message, $htmlRender));

            return $this->successResponse();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }
}
