@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>['']])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">پروژه ها - وب سایت</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item active">وب سایت ها</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">وب سایت ها</h3>
                    <div class="card-options">
                        <a href="{{ route('admin.project.web.create') }}" class="btn btn-success btn-sm">ایجاد پروژه</a>
                    </div>
                </div>

                <div class="card-body">
                    @include('admin.partial.message')
                    @if($projects->isNotEmpty())

                        <form class="row mb-4" action="{{ route('admin.project.web.index') }}">
                            <div class="col-12 col-md-3 col-xl-2">
                                <div class="form-group">
                                    <label class="form-label" for="id">شناسه</label>
                                    <input class="form-control form-control-sm " name="id" id="id" placeholder="شناسه">
                                </div>
                            </div>

                            <div class="col-12 col-md-3 col-xl-2">
                                <div class="form-group">
                                    <label class="form-label" for="domain">دامنه</label>
                                    <input class="form-control form-control-sm " name="domain" id="domain" placeholder="دامنه">
                                </div>
                            </div>

                            <div class="col-12 col-md-3 col-xl-2">
                                <div class="form-group">
                                    <label class="form-label" for="package_id">پکیج</label>
                                    <select name="package_id" id="package_id" class="form-control form-select form-select-sm">
                                        <option value="">طلایی</option>
                                        <option value="">پیشرفته</option>
                                        <option value="">اقتصادی</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3 col-xl-2">
                                <div class="form-group">
                                    <label class="form-label" for="status_id">وضعیت</label>
                                    <select name="status_id" id="status_id" class="form-control form-select form-select-sm">
                                        <option value="">در حال انجام</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3 col-xl-2">
                                <div class="form-group">
                                    <label class="form-label" for="sort">مرتب سازی</label>
                                    <select name="sort" id="sort" class="form-control form-select form-select-sm">
                                        <option value="id-desc">شناسه (صعودی)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3 col-xl-2 d-flex align-items-end justify-content-end">
                                <button class="btn btn-sm btn-block btn-primary mb-4">اعمال</button>
                            </div>

                        </form>

                        <div class="table-responsive">
                            <table id="data-table" class="table">
                                <thead>
                                <tr>
                                    <th>شناسه</th>
                                    <th>نام</th>
                                    <th>دامنه</th>
                                    <th>پکیج</th>
                                    <th>قیمت</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ تحویل</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($projects as $project)
                                    <tr>
                                        <td>{{ $project->id }}</td>
                                        <td>{{ $project->title }}</td>
                                        <td>{{ $project->domain }}</td>
                                        <td>{{ $project->type->package->title }}</td>
                                        <td>{{ number_format($project->price) }}</td>
                                        <td>{{ $project->status->title }}</td>
                                        <td>{{ verta($project->deadline_at)->format(formatJalaliDate()) }}</td>
                                        <td>{{ verta($project->created_at)->format(formatJalaliDate()) }}</td>
                                        <td>
                                            <a class="btn btn-warning btn-sm" href="{{ route('admin.project.web.edit',$project->id) }}">ویرایش</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $projects->withQueryString()->links() }}
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
    @include('admin.partial.loader.script',['load'=>['']])
@endsection
