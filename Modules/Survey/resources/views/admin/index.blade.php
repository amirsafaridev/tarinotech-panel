@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Datepicker(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <form class="row" action="{{ route('admin.survey.index') }}">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">{{ $title }}</div>
                    <div>
                        @can('ADMIN_SURVEY_CREATE')
                            <a class="btn btn-primary" href="{{ route('admin.survey.create') }}">ایجاد</a>
                        @endcan
                        <a class="btn btn-success datatable-export-button" href="{{ request()->fullUrlWithQuery(['export' => 'true']) }}" id="exportButton">خروجی Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>
                                <td>شناسه</td>
                                <td>عنوان</td>
                                <td>مدیر</td>
                                <td>تعداد سوالات</td>
                                <td>تعداد پاسخ‌ها</td>
                                <td>نیاز به احراز هویت</td>
                                <td>وضعیت</td>
                                <td>تاریخ شروع</td>
                                <td>تاریخ پایان</td>
                                <td>تاریخ ایجاد</td>
                                <td>عملیات</td>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <td>شناسه</td>
                                <td>عنوان</td>
                                <td>مدیر</td>
                                <td>تعداد سوالات</td>
                                <td>تعداد پاسخ‌ها</td>
                                <td>نیاز به احراز هویت</td>
                                <td>وضعیت</td>
                                <td>تاریخ شروع</td>
                                <td>تاریخ پایان</td>
                                <td>تاریخ ایجاد</td>
                                <td>عملیات</td>
                            </tr>
                            </tfoot>

                            <tbody>
                            @if($surveys->isNotEmpty())
                                @foreach($surveys as $survey)
                                    <tr>
                                        <td>{{ $survey->id }}</td>
                                        <td>{{ $survey->title }}</td>
                                        <td>{{ $survey->admin_mobile ?? 'نامشخص' }}</td>
                                        <td>{{ $survey->questions_count }}</td>
                                        <td>{{ $survey->responses_count }}</td>
                                        <td>{!! $survey->requires_auth ? '<span class="badge bg-success">بله</span>' : '<span class="badge bg-secondary">خیر</span>' !!}</td>
                                        <td>{!! $survey->is_active ? '<span class="badge bg-success">فعال</span>' : '<span class="badge bg-danger">غیرفعال</span>' !!}</td>
                                        <td>{{ $survey->start_date ? $survey->start_date->toJalali()->format(formatJalaliDate()) : 'نامشخص' }}</td>
                                        <td>{{ $survey->end_date ? $survey->end_date->toJalali()->format(formatJalaliDate()) : 'نامشخص' }}</td>
                                        <td>{{ $survey->created_at->toJalali()->format(formatJalaliDateTime()) }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a class="btn btn-sm btn-warning" href="{{ route('admin.survey.edit', $survey->id) }}">{{ __('panel.action.edit') }}</a>
                                                <a class="btn btn-sm btn-info" href="{{ route('admin.survey.question.index', $survey->id) }}">سوالات</a>
                                                {{--<a class="btn btn-sm btn-success" href="{{ route('admin.survey.report.index', $survey->id) }}">گزارش</a>--}}
                                                <a class="btn btn-sm btn-primary" href="{{ route('admin.survey.preview', $survey->id) }}" target="_blank">پیش‌نمایش</a>
                                                <a class="btn btn-sm btn-secondary me-2" href="{{ route('admin.survey.duplicate', $survey->id) }}">تکثیر نظرسنجی</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $surveys->links() }}
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Datepicker(),
    ]])
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function (){
            jalaliDatepicker.startWatch();
        })
    </script>
@endsection
