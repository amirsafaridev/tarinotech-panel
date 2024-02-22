<?php

namespace Modules\Chat\app\Http\Controllers\Admin;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Modules\Admin\app\Models\Admin;
use Modules\Chat\app\Events\Message\NewMessage;
use Modules\Chat\app\Http\Requests\Admin\Message\DestroyRequest;
use Modules\Chat\app\Http\Requests\Admin\Message\EditRequest;
use Modules\Chat\app\Http\Requests\Admin\Message\IndexRequest;
use Modules\Chat\app\Http\Requests\Admin\Message\StoreRequest;
use Modules\Chat\app\Http\Requests\Admin\Message\UpdateRequest;
use Modules\Chat\app\Resources\Message\MessageResource;
use Modules\Support\app\Models\Chat;
use Modules\Support\app\Models\ChatMessage;
use Modules\Support\app\Models\ChatMessageAttachment;
use Modules\Support\app\Models\ChatUser;
use View;

class MessageController extends Controller
{
    use HasJsonCommonResponse;

    const EDIT_TITLE = 'پیام - ویرایش';

    public function index(IndexRequest $request)
    {

        $messagesPaginator = ChatMessage::query()
            ->with(['user', 'attachments', 'replay'])
            ->where('chat_id', $request->input('chat_id'))
            ->orderBy('updated_at')
            ->paginate(100);

        $messages = $messagesPaginator->items();

        $messages = collect($messages)->map(function (ChatMessage $message) {
            $data = $message;
            $data['htmlRender'] = compressHtml(View::make('support::admin.part.row-message', ['message' => $message]));

            return $data;
        });

        /* Update For Counter */
        ChatUser::query()
            ->where('chat_id', $request->input('chat_id'))
            ->where('user_id', auth()->id())
            ->where('user_type', Admin::class)
            ->update([
                'seen_at' => now(),
                'unread' => 0,
            ]);

        return [
            'success' => true,
            'messages' => $messages,
            'pagination' => [
                'total' => $messagesPaginator->total(),
                'per_page' => $messagesPaginator->perPage(),
                'current_page' => $messagesPaginator->currentPage(),
                'last_page' => $messagesPaginator->lastPage(),
                'from' => $messagesPaginator->firstItem(),
                'to' => $messagesPaginator->lastItem(),
            ],
        ];
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $chatId = $request->input('chat_id');

            /* Find Chat */
            $chat = Chat::query()->findOrFail($chatId);

            /* Update Seen */
            ChatUser::query()
                ->where('user_id', auth()->id())
                ->where('chat_id', $chatId)
                ->update([
                    'seen_at' => now(),
                ]);

            /* Create Message */
            $message = ChatMessage::query()->create([
                'chat_id' => $request->input('chat_id'),
                'parent_id' => $request->input('parent_id'),
                'content' => $request->input('message'),
                'user_type' => Admin::class,
                'user_id' => auth()->id(),
            ]);

            /* Connect Attachment */
            if ($request->input('files')) {
                ChatMessageAttachment::query()
                    ->whereIn('id', $request->input('files'))
                    ->update([
                        'chat_message_id' => $message->id,
                    ]);
            }

            $chat->touch();

            if ($chat->type === ChatType::Ticket) {
                /*$chat->update([
                    'status' => ChatStatus::AdminAnswer,
                ]);*/
            }

            DB::commit();

            $htmlRender = compressHtml(View::make('support::admin.part.row-message', ['message' => $message]));

            event(new NewMessage(new MessageResource($message), $htmlRender));

            return response()->json([
                'htmlRender' => $htmlRender,
                'result' => 'success',
                'action' => 'store',
                'message' => trans('panel.success_store'),
            ]);

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(EditRequest $request)
    {
        try {
            $message = ChatMessage::query()
                ->with(['attachments', 'chat'])
                ->where('id', $request->input('message_id'))
                ->where('chat_id', $request->input('chat_id'))
                ->firstOrFail();

            $attachmentHtmlRender = '';
            if ($message->attachments->isNotEmpty()) {
                foreach ($message->attachments as $attachment) {
                    $attachmentHtmlRender .= compressHtml(View::make('support::admin.part.row-file-attachment', ['file' => $attachment]));
                }
            }

            return response()->json([
                'message' => $message,
                'attachmentHtmlRender' => $attachmentHtmlRender,
                'updateUrl' => route('admin.chat.message.update', $message->id),
                'result' => 'success',
            ]);

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function update(int $messageId, UpdateRequest $request)
    {
        try {

            /* Find Message */
            $message = ChatMessage::query()
                ->where('id', $messageId)
                ->where('chat_id', $request->input('chat_id'))
                ->firstOrFail();

            $message->update([
                'content' => $request->input('message'),
            ]);

            /* Connect Attachment */
            if ($request->input('files')) {
                ChatMessageAttachment::query()
                    ->whereIn('id', $request->input('files'))
                    ->update([
                        'chat_message_id' => $message->id,
                    ]);
            }

            $htmlRender = compressHtml(View::make('support::admin.part.row-message', ['message' => $message->load('attachments')]));

            return response()->json([
                'htmlRender' => $htmlRender,
                'result' => 'success',
                'action' => 'update',
                'messageId' => $message->id,
                'message' => trans('panel.success_update'),
            ]);

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(DestroyRequest $request)
    {
        try {
            /* Find Message */
            $message = ChatMessage::query()
                ->where('id', $request->input('message_id'))
                ->firstOrFail();

            /* Delete Message */
            $message->delete();

            return response()->json([
                'result' => 'success',
                'action' => 'destroy',
                'messageId' => $message->id,
                'message' => trans('panel.success_delete'),
            ]);
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }
}
