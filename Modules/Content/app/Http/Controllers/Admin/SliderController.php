<?php

namespace Modules\Content\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Helpers\Uploader\PhotoUploader;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatableTrait;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Modules\Content\app\Http\Requests\Admin\Slider\StoreRequest;
use Modules\Content\app\Http\Requests\Admin\Slider\UpdateRequest;
use Modules\Content\app\Models\Slider;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{
    use HasDatatableTrait;
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'اسلایدر ها';

    const CREATE_TITLE = 'اسلایدر ها - ایجاد';

    const EDIT_TITLE = 'اسلایدر ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('content::admin.slider.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('content::admin.slider.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            Slider::query()->create($this->prepareItemData($request));

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Slider $slider)
    {
        $title = self::EDIT_TITLE;

        return view('content::admin.slider.edit', compact('title', 'slider'));
    }

    public function update(UpdateRequest $request, Slider $slider)
    {
        try {
            $item = $this->prepareItemData($request);
            $item['slug'] = $request->input('slug');
            $slider->update($item);

            return $this->successUpdateResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Slider $slider)
    {
        try {
            $slider->delete();

            return $this->successDestroyBack(route('admin.content.slider.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);
        }
    }

    /**
     * @throws Exception
     */
    protected function prepareItemData(Request $req): array
    {
        $sliderData['title'] = $req->input('title');
        $sliderData['description'] = $req->input('description');
        $sliderData['link'] = $req->input('link');

        $publishedAt = Helper::toGregorian($req->input('published_at'));
        $sliderData['published_at'] = $publishedAt;

        $archivedAt = Helper::toGregorian($req->input('archived_at'));
        $sliderData['archived_at'] = $archivedAt;
        $sliderData['sort_id'] = 1;

        $sliderData['status'] = $req->boolean('status');

        if ($req->hasFile('photo')) {
            $imageUploader = (new PhotoUploader())
                ->fit(800, 450)
                ->path('slider')
                ->field('photo')
                ->upload();

            $sliderData['photo'] = $imageUploader->getPath();
        }

        return $sliderData;
    }

    public function getDataRoute(): string
    {
        return route('admin.content.slider.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()->setName('id')->setAs('شناسه')
                    ->setSearchable(true)
                    ->setSortable(true)
            )
            ->addColumn(
                ColumnOption::new()->setName('title')->setAs('عنوان')
                    ->setSearchable(true)
            )
            ->addColumn(
                ColumnOption::new()->setName('status')->setAs('فعال')
                    ->setSortable(true)
            )
            ->addColumn(
                ColumnOption::new()->setName('published_at')->setAs('تاریخ انتشار')
                    ->setSortable(true)
            )
            ->addColumn(
                ColumnOption::new()->setName('archived_at')->setAs('تاریخ آرشیو')
                    ->setSortable(true)
            )
            ->addColumn(
                ColumnOption::new()->setName('created_at')->setAs('ایجاد')
                    ->setSortable(true)
            )
            ->addColumn(
                ColumnOption::new()->setName('action')
                    ->setAs('عملیات')
                    ->removeAction()
            )
            ->render();
    }

    public function data()
    {
        try {
            $sliders = Slider::query();

            return DataTables::eloquent($sliders)
                ->editColumn('status', function ($slider) {
                    return $slider->status ? 'فعال' : 'غیر فعال';
                })
                ->editColumn('published_at', function ($slider) {
                    return $slider->published_at->toJalali()->format(formatJalaliDate());
                })
                ->editColumn('archived_at', function ($slider) {
                    return $slider->archived_at->()->format(formatJalaliDate());
                })
                ->editColumn('created_at', function ($slider) {
                    return $slider->created_at->()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function ($slider) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.content.slider.edit', $slider->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
