<?php

namespace Modules\Blog\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Foundation\ValueObjects\Datatable\ExternalFilter;
use App\Helpers\Helper;
use App\Helpers\Uploader\PhotoUploader;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Database\Eloquent\Builder;
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

        $dataTable = $this->getDataTable();

        return view('blog::admin.index', compact('title', 'routeData', 'dataTable'));
    }

    public function data()
    {
        try {
            $blogs = Blog::query()
                ->when(is_numeric(request('category_id')), function (Builder $q) {
                    return $q->where('blog_category_id', request('category_id'));
                })
                ->with(['category']);

            return DataTables::eloquent($blogs)
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
        return route('admin.blog.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()->setName('id')->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()->setName('title')->setAs('عنوان')
            )
            ->addColumn(
                ColumnOption::new()->setName('category.title')->setAs('دسته بندی')
            )
            ->addColumn(
                ColumnOption::new()->setName('created_at')->setAs('ایجاد')
            )
            ->addColumn(
                ColumnOption::new()->setName('action')
                    ->setAs('عملیات')
                    ->removeAction()
            )
            ->addExternalFilter(ExternalFilter::new()->setKey('category_id'))
            ->render();
    }
}
