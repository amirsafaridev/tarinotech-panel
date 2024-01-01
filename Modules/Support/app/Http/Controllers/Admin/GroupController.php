<?php

namespace Modules\Support\app\Http\Controllers\Admin;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use App\Helpers\Uploader\PhotoUploader;
use App\Http\Controllers\Controller;
use App\Models\PresenterProject;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Admin\app\Models\Admin;
use Modules\Project\app\Models\Project;
use Modules\Support\app\Http\Requests\Admin\StoreRequest;
use Modules\Support\app\Http\Requests\Admin\UpdateRequest;
use Modules\Support\app\Models\Chat;
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
        sleep(2);
        $chatsPaginator = Chat::query()
            ->select(['id', 'title', 'logo', 'updated_at', 'created_at'])
            ->with([
                'users.user:id,avatar,first_name,last_name',
                'project:id,title,domain',
            ])
            ->where('type', ChatType::Group)
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

        return view('support::admin.group.edit', compact('title', 'chat'));
    }

    public function update(UpdateRequest $request, Chat $chat)
    {
        try {
            $item = $this->prepareItemData($request);
            $item['slug'] = $request->input('slug');
            $chat->update($item);

            return $this->successUpdateResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Chat $chat)
    {
        try {
            $chat->delete();

            return $this->successDestroyBack(route('admin.blog.index'));
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
        $chatData['status'] = ChatStatus::Open;
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
}
