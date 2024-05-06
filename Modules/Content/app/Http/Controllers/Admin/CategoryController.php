<?php

namespace Modules\Content\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Modules\Content\app\Events\CategoryWasDeleted;
use Modules\Content\app\Http\Requests\Admin\Category\StoreRequest;
use Modules\Content\app\Http\Requests\Admin\Category\UpdateRequest;
use Modules\Content\app\Models\BlogCategory;
use Yajra\DataTables\Facades\DataTables;

use function event;
use function formatJalaliDateTime;
use function route;
use function trans;
use function view;

class CategoryController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'دسته بندی بلاگ ها';

    const CREATE_TITLE = 'دسته بندی بلاگ ها - ایجاد';

    const EDIT_TITLE = 'دسته بندی بلاگ ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('content::admin.blog.category.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('content::admin.blog.category.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {

            BlogCategory::query()->create($this->prepareItemData($request));

            return $this->successResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function edit(BlogCategory $blogCategory)
    {
        $title = self::EDIT_TITLE;

        return view('content::admin.blog.category.edit', compact('title', 'blogCategory'));
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

            event(new CategoryWasDeleted($blogCategory->id));

            $blogCategory->delete();

            return $this->successDestroyBack(route('admin.content.blog.category.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['title'] = $request->input('title');

        return $item;
    }

    public function getDataRoute(): string
    {
        return route('admin.content.blog.category.data');
    }

    public function getDataTable(): array
    {
        $dataTable = new DatatableBase();

        $dataTable
            ->addColumn(
                ColumnOption::new()
                    ->setName('id')
                    ->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('title')
                    ->setAs('عنوان')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('blogs_count')
                    ->setAs('تعداد بلاگ')
                    ->setSearchable(false)
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('created_at')
                    ->setAs('ایجاد')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('action')
                    ->setAs('عملیات')
                    ->removeAction()
            );

        return $dataTable->render();
    }

    public function data()
    {
        try {
            $categories = BlogCategory::query()
                ->withCount('blogs');

            return DataTables::eloquent($categories)
                ->editColumn('created_at', function (BlogCategory $category) {
                    return $category->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (BlogCategory $category) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.content.blog.category.edit', $category->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
