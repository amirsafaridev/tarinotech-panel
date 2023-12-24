<?php

namespace Modules\Support\app\Http\Controllers\Admin;

use App\Helpers\Uploader\PhotoUploader;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Modules\Support\app\Http\Requests\Admin\StoreRequest;
use Modules\Support\app\Http\Requests\Admin\UpdateRequest;
use Modules\Support\app\Models\Chat;

class GroupController extends Controller
{
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'گروه ها';

    const CREATE_TITLE = 'گروه ها - ایجاد';

    const EDIT_TITLE = 'گروه ها - ویرایش';

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('support::admin.group.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Chat $chat)
    {
        $title = self::EDIT_TITLE;

        return view('blog::admin.edit', compact('title', 'chat'));
    }

    public function update(UpdateRequest $request, Blog $chat)
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

    public function destroy(Blog $chat)
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
        $chatData['blog_category_id'] = $req->input('blog_category_id');
        $chatData['body'] = $req->input('body');
        $chatData['meta_description'] = $req->input('meta_description');
        $chatData['meta_keywords'] = $req->input('meta_keywords');
        $chatData['is_publish'] = $req->has('is_publish');

        if ($req->hasFile('photo')) {
            $imageUploader = (new PhotoUploader())
                ->fit(500, 500)
                ->path('blog')
                ->field('photo')
                ->upload();

            $chatData['photo'] = $imageUploader->getPath();
        }

        return $chatData;
    }
}
