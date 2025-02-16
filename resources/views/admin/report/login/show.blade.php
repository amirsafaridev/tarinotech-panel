@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')

@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">اطلاعات ورود</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.report.login') }}">گزارش ورود</a></li>
                <li class="breadcrumb-item active">نمایش اطلاعات</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>

                            <tr>
                                <td>شناسه</td>
                                <td>{{ $login->id }}</td>
                            </tr>

                            <tr>
                                <td>تاریخ ایجاد</td>
                                <td>{{$login->created_at->toJalali()->format('d F Y - H:i')}}</td>
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
