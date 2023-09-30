@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')

@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">انواع پروژه</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project-type.index') }}">انواع پروژه</a></li>
                <li class="breadcrumb-item active">نمایش نوع</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">اطلاعات کاربر</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <td>شناسه</td>
                                <td>{{ $projectType->id }}</td>
                            </tr>

                            <tr>
                                <td>عنوان</td>
                                <td>{{ $projectType->title }}</td>
                            </tr>

                            <tr>
                                <td>تعداد پروژه ها</td>
                                <td>{{ number_format($projectType->projects_count) }}</td>
                            </tr>

                            <tr>
                                <td>تاریخ ایجاد</td>
                                <td>{{$projectType->created_at->toJalali()->format(formatJalaliDateTime())}}</td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
