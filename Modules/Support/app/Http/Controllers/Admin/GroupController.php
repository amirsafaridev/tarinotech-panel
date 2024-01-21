<?php

namespace Modules\Support\app\Http\Controllers\Admin;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use App\Helpers\Uploader\PhotoUploader;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\app\Models\PresenterProject;
use Modules\Project\app\Models\Project;
use Modules\Support\app\Http\Requests\Admin\Group\StoreRequest;
use Modules\Support\app\Http\Requests\Admin\Group\UpdateRequest;
use Modules\Support\app\Models\Chat;
use Modules\Support\app\Models\ChatUser;
use Modules\User\app\Models\User;
use View;

class GroupController extends Controller
{
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'گروه ها';

    const CREATE_TITLE = 'گروه ها - ایجاد';

    const EDIT_TITLE = 'گروه ها - ویرایش';

    public function index()
    {
        $search = request()->input('search');

        $chatsPaginator = Chat::query()
            ->select(['id', 'title', 'logo', 'status', 'project_id', 'updated_at', 'created_at'])
            ->with(['users.user', 'project'])
            ->when(! empty($search), function ($q) use ($search) {
                $q->whereHas('project', function ($q) use ($search) {
                    return $q->where('title', 'like', "%{$search}%")
                        ->where('domain', 'like', "%{$search}%");
                });
                $q->orWhere('title', 'like', "%{$search}%");
            })
            ->where('type', ChatType::Group)
            ->orderByDesc('updated_at')
            ->paginate(20);

        $chats = $chatsPaginator->items();

        $chats = collect($chats)->map(function (Chat $chat) {
            $data = $chat;
            $data['htmlRender'] = compressHtml(View::make('support::admin.part.row-group', ['chat' => $chat]));

            return $data;
        });

        return [
            'success' => true,
            'chats' => $chats,
            'pagination' => [
                'total' => $chatsPaginator->total(),
                'per_page' => $chatsPaginator->perPage(),
                'current_page' => $chatsPaginator->currentPage(),
                'last_page' => $chatsPaginator->lastPage(),
                'from' => $chatsPaginator->firstItem(),
                'to' => $chatsPaginator->lastItem(),
            ],
        ];

    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('support::admin.group.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {

            DB::beginTransaction();
            $chat = Chat::query()->create($this->prepareItemData($request));

            $projectId = $request->input('project_id');

            $this->attachAdminUsers($chat, $request->get('admin_id'));
            $this->attachProjectUser($chat, $projectId);
            $this->attachPresenterUsers($chat, $projectId);

            DB::commit();

            return $this->successResponse();

        } catch (Exception $exception) {

            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Chat $chat)
    {
        $title = self::EDIT_TITLE;

        $chat->load('users');

        $oldUsers = [];
        if ($chat->users->isNotEmpty()) {
            $oldUsers = $chat->users->pluck('user_id')->toArray();
        }

        return view('support::admin.group.edit', compact('title', 'chat', 'oldUsers'));
    }

    public function update(UpdateRequest $request, Chat $chat)
    {
        try {
            DB::beginTransaction();

            $chat->update($this->prepareItemData($request));

            $projectId = $request->input('project_id');

            $this->detachAllUser($chat);
            $this->attachAdminUsers($chat, $request->get('admin_id'));
            $this->attachProjectUser($chat, $projectId);
            $this->attachPresenterUsers($chat, $projectId);

            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Chat $chat)
    {
        try {
            $chat->delete();

            return $this->successDestroyBack(route('admin.support.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);
        }
    }

    /**
     * @throws Exception
     */
    protected function prepareItemData(Request $req): array
    {
        $chatData['title'] = $req->input('title');
        $chatData['type'] = ChatType::Group;
        $chatData['status'] = $req->input('status', ChatStatus::Open);
        $chatData['project_id'] = $req->input('project_id');

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

    private function attachProjectUser(Chat $chat, $projectId)
    {
        $projectUser = Project::query()
            ->select(['id', 'user_id'])
            ->findOrFail($projectId);

        $chat->users()->create([
            'user_id' => $projectUser->user_id,
            'user_type' => User::class,
            'seen_at' => now(),
        ]);
    }

    private function attachPresenterUsers(Chat $chat, $projectId)
    {
        $presenterIds = PresenterProject::query()
            ->where('project_id', $projectId)
            ->pluck('user_id');

        foreach ($presenterIds as $presenterId) {
            $chat->users()->create([
                'user_id' => $presenterId,
                'user_type' => User::class,
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
