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

    <form class="row" action="{{ route('admin.ticket.index') }}">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">{{ $title }}</div>
                    <div>
                        <a class="btn btn-primary me-2" href="{{ route('admin.ticket.create') }}">
                            <i class="fa fa-plus"></i> ایجاد تیکت جدید
                        </a>
                        <a class="btn btn-success datatable-export-button"
                           href="{{ request()->fullUrlWithQuery(['export' => 'true']) }}" id="exportButton">خروجی
                            Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    @include('ticket::admin.part.filter')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>
                                <td>شناسه</td>
                                <td>عنوان</td>
                                <td>کاربر</td>
                                <td>پشتیبان</td>
                                <td>موضوع</td>
                                <td>وضعیت</td>
                                <td>اولویت</td>
                                <td>آخرین پاسخ</td>
                                <td>امتیاز</td>
                                <td>عملیات</td>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <td>شناسه</td>
                                <td>عنوان</td>
                                <td>کاربر</td>
                                <td>پشتیبان</td>
                                <td>موضوع</td>
                                <td>وضعیت</td>
                                <td>اولویت</td>
                                <td>آخرین پاسخ</td>
                                <td>امتیاز</td>
                                <td>عملیات</td>
                            </tr>
                            </tfoot>

                            <tbody>
                            @if($tickets->isNotEmpty())
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->id }}</td>
                                        <td>{{ $ticket->title }}</td>
                                        <td>{{ $ticket->user_first_name }} {{ $ticket->user_last_name }}</td>
                                        <td>
                                            @if($ticket->assigned_to)
                                                {{ $ticket->admin_first_name }} {{ $ticket->admin_last_name }}
                                            @else
                                                <span class="badge bg-warning">تعیین نشده</span>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->subject_title }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $ticket->status_color }}">
                                                {{ $ticket->status_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $ticket->priority_color }}">
                                                {{ $ticket->priority_name }}
                                            </span>
                                        </td>
                                        <td>{{ optional($ticket->last_response_at)->toJalali()->format(formatJalaliDate()) ?? 'بدون پاسخ' }}</td>
                                        <td>{{ $ticket->rating ? $ticket->rating . '/5' : 'ثبت نشده' }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-success"
                                               href="{{ route('admin.ticket.manage', $ticket->chat_id) }}">{{ __('panel.action.manage') }}</a>
                                            <a class="btn btn-sm btn-info"
                                               href="{{ route('admin.ticket.edit', $ticket->chat_id) }}"><i
                                                    class="fe fe-edit"></i> ویرایش</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $tickets->links() }}
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
