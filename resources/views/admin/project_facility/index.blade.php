@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[\App\Enums\Assets\StyleLoader::DataTable()]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">امکانات جانبی</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">{{ $project->title }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.'.getRouteProjectType($project->project_base_id).'.show',$project->id) }}">پروژه ها</a></li>
                <li class="breadcrumb-item active">امکانات جانبی</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $project->title }} | {{ $project->domain }}</h3>
                    <a class="btn btn-success btn-sm" href="{{ route('admin.project.facility.create',1) }}">ایجاد</a>
                </div>

                <div class="card-body">
                    @include('admin.partial.message')



                    @if($facilities->isNotEmpty())
                        <div class="table-responsive">
                            <table id="data-table" class="table">
                                <thead>
                                <tr>
                                    <th>شناسه</th>
                                    <th>امکان جانبی</th>
                                    <th>قیمت ایجاد</th>
                                    <th>سیکل کاری تمدید</th>
                                    <th>سیکل مالی</th>
                                    <th>محاسبه</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ ثبت</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($facilities as $facility)
                                    <tr>
                                        <td>{{ $facility->id }}</td>
                                        <td>{{ $facility->facility->title }}</td>
                                        <td>{{ \App\Enums\Database\Facility\PriceType::getDescription($facility->price_type) }}</td>
                                        <td>{{ \App\Enums\Database\Facility\WorkCycle::getDescription($facility->work_cycle) }}</td>
                                        <td>{{ \App\Enums\Database\Facility\FinancialCycle::getDescription($facility->financial_cycle) }}</td>
                                        <td>0</td>
                                        <td>{{ \App\Enums\Database\Facility\FacilityStatus::getDescription($facility->status) }}</td>
                                        <td>{{ verta($facility->added_at)->format(formatJalaliDate()) }}</td>
                                        <td>{{ verta($facility->created_at)->format(formatJalaliDate()) }}</td>
                                        <td>
                                            <a target="_blank" class="btn btn-warning btn-sm">ویرایش</a>
                                            <button type="button" class="btn btn-danger btn-sm">حذف</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p>پروژه ای یافت نشد!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
