<?php

namespace Modules\BlogCategory\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Modules\BlogCategory\app\Events\BlogCategoryWasDeleted;
use Modules\BlogCategory\app\Http\Requests\Admin\StoreRequest;
use Modules\BlogCategory\app\Http\Requests\Admin\UpdateRequest;
use Modules\BlogCategory\app\Models\BlogCategory;

class BlogCategoryController extends Controller
{
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'دسته بندی بلاگ ها';

    const CREATE_TITLE = 'دسته بندی بلاگ ها - ایجاد';

    const EDIT_TITLE = 'دسته بندی بلاگ ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $blogCategories = BlogCategory::query()
            ->withCount('blogs')
            ->whereNot('id', 1)
            ->latest()
            ->paginate(10);

        return view('blogcategory::admin.index', compact('title', 'blogCategories'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('blogcategory::admin.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {

            BlogCategory::query()->create($this->prepareItemData($request));

            return $this->successResponse();
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function edit(BlogCategory $blogCategory)
    {
        $title = self::EDIT_TITLE;

        return view('blogcategory::admin.edit', compact('title', 'blogCategory'));
    }

    public function update(UpdateRequest $request, BlogCategory $blogCategory)
    {
        try {

            $item = $this->prepareItemData($request);
            $item['slug'] = $request->input('slug');
            $blogCategory->update($item);

            return $this->successUpdateResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(BlogCategory $blogCategory)
    {
        try {
            if ($blogCategory->id === 1) {
                return $this->errorBack('دسته بندی پیش فرض قابل حذف نیست!');
            }

            event(new BlogCategoryWasDeleted($blogCategory->id));

            $blogCategory->delete();

            return $this->successDestroyBack(route('admin.blog.category.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['title'] = $request->input('title');

        return $item;
    }
}
