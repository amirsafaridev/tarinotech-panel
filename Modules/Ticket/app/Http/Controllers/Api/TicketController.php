<?php

namespace Modules\Ticket\app\Http\Controllers\Api;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Modules\Chat\app\Resources\Chat\ChatResource;
use Modules\Project\app\Models\Project;
use Modules\Support\app\Models\Chat;
use Modules\Ticket\app\Http\Requests\Api\StoreRequest;
use Modules\User\app\Models\User;

class TicketController extends Controller
{
    use HasApiResponse;

    public function store(StoreRequest $request)
    {
        try {

            Project::query()
                ->where('id', $request->input('project_id'))
                ->where('user_id', auth()->id())
                ->orWhereHas('presenter', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->firstOrFail();

            $checkOpenedChat = Chat::query()
                ->where('project_id', $request->input('project_id'))
                ->where('type', ChatType::Ticket)
                ->whereNot('status', ChatStatus::Close)
                ->with('project')
                ->first();

            if ($checkOpenedChat) {
                $chatResourced = new ChatResource($checkOpenedChat);

                return $this->successResponse($chatResourced, 'مشاهده تیکت');
            }

            $chat = Chat::query()
                ->create([
                    'title' => 'تیکت',
                    'project_id' => $request->input('project_id'),
                    'type' => ChatType::Ticket,
                    'status' => ChatStatus::Open,
                ]);

            $chat->users()->create([
                'user_id' => auth()->id(),
                'user_type' => User::class,
                'seen_at' => now(),
            ]);

            $chatResourced = new ChatResource($chat->load('project'));

            return $this->successResponse($chatResourced, 'تیکت با موفقیت ایجاد شد.');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
