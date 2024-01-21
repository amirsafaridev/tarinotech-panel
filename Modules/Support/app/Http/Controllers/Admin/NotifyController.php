<?php

namespace Modules\Support\app\Http\Controllers\Admin;

use App\Enums\Database\Chat\ChatType;
use App\Helpers\Uploader\PhotoUploader;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Admin\app\Models\Admin;
use Modules\Support\app\Http\Requests\Admin\Notify\UpdateRequest;
use Modules\Support\app\Models\Chat;
use Modules\Support\app\Models\ChatUser;

class NotifyController
{
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'اطلاعیه ها';

    const EDIT_TITLE = 'اطلاعیه ها - ویرایش';

    public function index()
    {
        $chat = Chat::query()
            ->where('type', ChatType::Public)
            ->firstOrFail();

        $title = self::INDEX_TITLE;

        return view('support::admin.notify.index', compact('title', 'chat'));

    }

    public function edit(Chat $chat)
    {
        $title = self::EDIT_TITLE;

        $chat->load('users');

        $oldUsers = [];
        if ($chat->users->isNotEmpty()) {
            $oldUsers = $chat->users->pluck('user_id')->toArray();
        }

        return view('support::admin.notify.edit', compact('title', 'chat', 'oldUsers'));
    }

    public function update(UpdateRequest $request, Chat $chat)
    {
        try {
            DB::beginTransaction();

            $chat->update($this->prepareItemData($request));

            $this->detachAllUser($chat);
            $this->attachAdminUsers($chat, $request->get('admin_id'));

            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    /**
     * @throws Exception
     */
    protected function prepareItemData(Request $req): array
    {
        $chatData['title'] = $req->input('title');
        if ($req->hasFile('logo')) {
            $imageUploader = (new PhotoUploader())
                ->fit(250, 250)
                ->path('chat/logo')
                ->field('logo')
                ->upload();

            $chatData['logo'] = $imageUploader->getPath();
        }

        return $chatData;
    }

    private function attachAdminUsers(Chat $chat, array $adminIds)
    {
        foreach ($adminIds as $adminId) {
            $chat->users()->create([
                'user_id' => $adminId,
                'user_type' => Admin::class,
                'seen_at' => now(),
            ]);
        }
    }

    private function detachAllUser(Chat $chat): void
    {
        ChatUser::query()
            ->where('chat_id', $chat->id)
            ->delete();
    }
}
