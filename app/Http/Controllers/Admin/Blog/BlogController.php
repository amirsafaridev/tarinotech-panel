<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Helpers\Uploader\Uploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\StoreRequest;
use App\Http\Requests\Admin\Blog\UpdateRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    public function index()
    {
        $title = 'بلاگ ها';
        $routeData = route('admin.blog.data');
        $selects = ['id', 'title', 'category.title', 'created_at'];

        return view('admin.blog.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {

        try {
            $blogs = Blog::query()->select('blogs.*')
                ->with('category');

            return DataTables::of($blogs)
                ->editColumn('created_at', function (Blog $blog) {
                    return $blog->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->editColumn('category.title', function (Blog $blog) {
                    return $blog->category->title ?? 'بدون دسته بندی';
                })
                ->addColumn('action', function (Blog $blog) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.blog.edit', $blog->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = 'بلاگ ها - جدید';
        $routeStore = route('admin.blog.store');
        $categories = BlogCategory::query()->get();

        return view('admin.blog.create', compact('title', 'routeStore', 'categories'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $item = $this->itemProvider($request);
            Blog::query()->create($item);

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_store'),
            ]);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => trans('panel.error_store'),
            ], 500);
        }
    }

    public function edit(Blog $blog)
    {
        $title = 'بلاگ ها - ویرایش';
        $routeUpdate = route('admin.blog.update', $blog->id);
        $routeDestroy = route('admin.blog.destroy', $blog->id);
        $categories = BlogCategory::query()->get();

        return view('admin.blog.edit', compact('title', 'routeUpdate', 'routeDestroy', 'categories', 'blog'));
    }

    public function update(UpdateRequest $request, Blog $blog)
    {
        try {
            $item = $this->itemProvider($request);
            $item['slug'] = $request->input('slug');
            $blog->update($item);

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_update'),
            ]);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => trans('panel.error_update'),
            ], 500);
        }
    }

    public function destroy(Blog $blog)
    {
        try {
            $blog->delete();

            return redirect(route('admin.blog.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.blog.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['blog_category_id'] = $request->input('blog_category_id');
        $item['body'] = $request->input('body');
        $item['meta_description'] = $request->input('meta_description');
        $item['meta_keywords'] = $request->input('meta_keywords');
        $item['is_publish'] = $request->has('is_publish');

        if ($request->hasFile('photo')) {
            $provider = (new Uploader())
                ->fit(500, 500)
                ->path('blog')
                ->field('photo')
                ->upload();

            $item['photo'] = $provider['photo'];
        }

        return $item;
    }
}
