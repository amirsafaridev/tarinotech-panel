<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategory\StoreRequest;
use App\Http\Requests\Admin\BlogCategory\UpdateRequest;
use App\Models\BlogCategory;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $title = 'بلاگ ها - دسته بندی ها';
        $routeData = route('admin.blog.category.data');
        $selects = ['id', 'title', 'blogs_count', 'created_at'];

        return view('admin.blog_category.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {
        try {
            $categories = BlogCategory::query()
                ->withCount('blogs');

            return DataTables::of($categories)
                ->editColumn('created_at', function (BlogCategory $blogCategory) {
                    return $blogCategory->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (BlogCategory $blogCategory) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.blog.category.edit', $blogCategory->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = 'بلاگ ها - دسته بندی جدید';
        $routeStore = route('admin.blog.category.store');

        return view('admin.blog_category.create', compact('title', 'routeStore'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $item = $this->itemProvider($request);
            BlogCategory::create($item);

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

    public function edit(BlogCategory $blogCategory)
    {
        $title = 'بلاگ ها - ویرایش دسته بندی';
        $routeUpdate = route('admin.blog.category.update', $blogCategory->id);
        $routeDestroy = route('admin.blog.category.destroy', $blogCategory->id);

        return view('admin.blog_category.edit', compact('title', 'routeUpdate', 'routeDestroy', 'blogCategory'));
    }

    public function update(UpdateRequest $request, BlogCategory $blogCategory)
    {
        try {

            $item = $this->itemProvider($request);
            $blogCategory->slug = $request->input('slug');
            $blogCategory->update($item);

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

    public function destroy(BlogCategory $blogCategory)
    {
        try {
            $blogCategory->delete();

            return redirect(route('admin.blog.category.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.blog.category.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['title'] = $request->input('title');

        return $item;
    }
}
