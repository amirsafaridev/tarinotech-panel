@php use App\Enums\Assets\StyleLoader; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[StyleLoader::DataTable()]])
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

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">موضوعات تیکت</h3>
                    @can('ADMIN_TICKET_INDEX')
                        <a class="btn btn-success btn-sm" href="{{ route('admin.ticket.subjects.create') }}">ایجاد
                            موضوع</a>
                    @endcan
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>
                                <th>شناسه</th>
                                <th>عنوان</th>
                                <th>وضعیت انتشار</th>
                                <th>تاریخ ایجاد</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($subjects->isNotEmpty())
                                @foreach($subjects as $subject)
                                    <tr>
                                        <td>{{ $subject->id }}</td>
                                        <td>{{ $subject->title }}</td>
                                        <td>
                                            @if($subject->is_published)
                                                <span class="badge bg-success">منتشر شده</span>
                                            @else
                                                <span class="badge bg-secondary">غیرفعال</span>
                                            @endif
                                        </td>
                                        <td>{{ $subject->created_at->toJalali()->format(formatJalaliDateTime())  }}</td>
                                        <td>
                                            @if($subject->trashed())
                                                <form
                                                    action="{{ route('admin.ticket.subjects.restore', $subject->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm">بازگردانی
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.ticket.subjects.edit', $subject->id) }}"
                                                   class="btn btn-warning btn-sm">ویرایش</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[ScriptLoader::DataTable()]])
    @include('admin.partial.datatable_offline')
@endsection
