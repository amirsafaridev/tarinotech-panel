@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')

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

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    @include('admin.partial.message')

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @include('login::admin.filter')
                    @if($logins->isNotEmpty())
                        <div class="table-responsive">
                            <table id="data-table" class="table">
                                <thead>
                                <tr>
                                    <th>نوع کاربر</th>
                                    <th>پروفایل</th>
                                    <th>تاریخ ورود</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($logins as $item)
                                    <tr>
                                        <td>{{ getEventCauserType($item->user_type) }}</td>
                                        <td>{!! getCauserProfile($item->user) !!}</td>


                                        <td>{{ verta($item->login_at)->format(formatJalaliDateTime()) }}</td>
                                        <td>{{ verta($item->created_at)->format(formatJalaliDateTime()) }}</td>
                                        <td>
                                            <a target="_blank" class="btn btn-info btn-sm" href="{{ route('admin.log.show',$item->id) }}">نمایش</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $logins->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p>ورودی یافت نشد!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
