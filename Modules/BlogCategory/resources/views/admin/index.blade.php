@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')

@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">{{ $title }}</div>
                    <a class="btn btn-primary" href="{{ route('admin.blog.category.create') }}">ایجاد دسته بندی</a>
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    @if($blogCategories->isNotEmpty())
                        <div class="table-responsive">
                            <table id="data-table" class="table">
                                <thead>
                                <tr>
                                    <th>شناسه</th>
                                    <th>نام</th>
                                    <th>تعداد بلاگ</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($blogCategories as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->blogs_count }}</td>
                                        <td>{{ verta($item->created_at)->format(formatJalaliDate()) }}</td>
                                        <td>
                                            <a target="_blank" class="btn btn-warning btn-sm" href="{{ route('admin.blog.category.edit',$item->id) }}">ویرایش</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $blogCategories->withQueryString()->links() }}
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
