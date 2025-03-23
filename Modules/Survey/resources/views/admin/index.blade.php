@php use App\Enums\Assets\StyleLoader; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        StyleLoader::Datepicker(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
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
                        <a class="btn btn-success datatable-export-button"
                           href="{{ request()->fullUrlWithQuery(['export' => 'true']) }}" id="exportButton">خروجی
                            Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    @include('survey::admin.part.filter')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>
                                <td>شناسه</td>
                                <td>عنوان</td>
                                <td>کارشناس</td>
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
                                            <div class="dropdown position-static">
                                                <button class="btn btn-sm btn-primary dropdown-toggle"
                                                        type="button"
                                                        id="surveyActionDropdown-{{ $survey->id }}"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                    عملیات
                                                </button>
                                                <ul class="dropdown-menu position-absolute"
                                                    style="right: auto; z-index: 1000;"
                                                    aria-labelledby="surveyActionDropdown-{{ $survey->id }}">

                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('survey.public.show', $survey->access_token) }}"
                                                           target="_blank">
                                                            <i class="fas fa-eye me-2"></i> نمایش
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.survey.edit', $survey->id) }}">
                                                            <i class="fas fa-edit me-2"></i> {{ __('panel.action.edit') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.survey.question.index', $survey->id) }}">
                                                            <i class="fas fa-question-circle me-2"></i> سوالات
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.survey.response.index', $survey->id) }}">
                                                            <i class="fas fa-reply me-2"></i> پاسخ‌ها
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.survey.report.index', ['survey' => $survey->id]) }}">
                                                            <i class="fas fa-chart-bar me-2"></i> گزارش
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.survey.duplicate', $survey->id) }}">
                                                            <i class="fas fa-copy me-2"></i> تکثیر نظرسنجی
                                                        </a>
                                                    </li>
                                                </ul>
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
        ScriptLoader::Datepicker(),
    ]])
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function () {
            jalaliDatepicker.startWatch();
        })
    </script>
@endsection
