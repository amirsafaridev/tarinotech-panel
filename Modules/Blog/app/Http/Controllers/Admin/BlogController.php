<?php

namespace Modules\Blog\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Helpers\Uploader\PhotoUploader;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Modules\Blog\app\Http\Requests\Admin\StoreRequest;
use Modules\Blog\app\Http\Requests\Admin\UpdateRequest;
use Modules\Blog\app\Models\Blog;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'بلاگ ها';

    const CREATE_TITLE = 'بلاگ ها - ایجاد';

    const EDIT_TITLE = 'بلاگ ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $selects = $this->getColumns();

        return view('blog::admin.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {
        try {
            $blogs = Blog::query()
                ->with(['category'])
                ->get();

            return DataTables::of($blogs)
                ->editColumn('created_at', function ($blog) {
                    return $blog->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function ($blog) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.blog.edit', $blog->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('blog::admin.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            Blog::query()->create($this->prepareItemData($request));

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Blog $blog)
    {
        $title = self::EDIT_TITLE;

        return view('blog::admin.edit', compact('title', 'blog'));
    }

    public function update(UpdateRequest $request, Blog $blog)
    {
        try {
            $item = $this->prepareItemData($request);
            $item['slug'] = $request->input('slug');
            $blog->update($item);

            return $this->successUpdateResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Blog $blog)
    {
        try {
            $blog->delete();

            return $this->successBack(route('admin.blog.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);
        }
    }

    /**
     * @throws Exception
     */
    protected function prepareItemData(Request $req): array
    {
        $blogData['title'] = $req->input('title');
        $blogData['blog_category_id'] = $req->input('blog_category_id');
        $blogData['body'] = $req->input('body');
        $blogData['meta_description'] = $req->input('meta_description');
        $blogData['meta_keywords'] = $req->input('meta_keywords');
        $blogData['is_publish'] = $req->has('is_publish');

        if ($req->hasFile('photo')) {
            $imageUploader = (new PhotoUploader())
                ->fit(500, 500)
                ->path('blog')
                ->field('photo')
                ->upload();

            $blogData['photo'] = $imageUploader->getPath();
        }

        return $blogData;
    }

    public function getDataRoute(): string
    {
        $this->routeData = route('admin.blog.data');

        return $this->routeData;
    }

    public function getColumns(): array
    {
        $this->columns = ['id', 'title', 'category.title', 'created_at'];

        return $this->columns;
    }
}
