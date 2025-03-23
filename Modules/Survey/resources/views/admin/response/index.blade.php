@php use App\Enums\Assets\StyleLoader; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        StyleLoader::Datepicker(),
        StyleLoader::Toast(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">نظرسنجی‌ها</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.survey.edit', $survey->id) }}">{{ $survey->title }}</a></li>
                <li class="breadcrumb-item active">پاسخ‌ها</li>
            </ol>
        </div>
    </div>

    <form class="row" action="{{ route('admin.survey.response.index',$survey->id) }}">
        <!-- Survey stats cards -->
        <div class="col-xl-12">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                        <span class="avatar avatar-md bg-primary-transparent me-3">
                            <i class="fa fa-clipboard-list"></i>
                        </span>
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $totalResponses }}</h5>
                                    <p class="text-muted mb-0 small">تعداد کل پاسخ‌ها</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                        <span class="avatar avatar-md bg-success-transparent me-3">
                            <i class="fa fa-check-circle"></i>
                        </span>
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $completionRate }}%</h5>
                                    <p class="text-muted mb-0 small">نرخ تکمیل</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                        <span class="avatar avatar-md bg-warning-transparent me-3">
                            <i class="fa fa-clock"></i>
                        </span>
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $averageResponseTime }}</h5>
                                    <p class="text-muted mb-0 small">میانگین زمان پاسخگویی</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responses list -->
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">لیست پاسخ‌ها</div>
                    <a class="btn btn-success datatable-export-button"
                       href="{{ request()->fullUrlWithQuery(['export' => 'true']) }}" id="exportButton">خروجی Excel</a>
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    @include('survey::admin.part.filter-response')

                    @if($responses->isEmpty())
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i>
                            هنوز هیچ پاسخی برای این نظرسنجی ثبت نشده است.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>نام پاسخ دهنده</th>
                                    <th>ایمیل</th>
                                    <th>آی‌پی</th>
                                    <th>تعداد پاسخ‌ها</th>
                                    <th>تاریخ ثبت</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($responses as $index => $response)
                                    <tr>
                                        <td>{{ $response->id }}</td>
                                        <td>
                                            @if($response->respondent_name)
                                                {{ $response->respondent_name }}
                                            @elseif($response->user_id)
                                                <span class="badge bg-secondary">کاربر #{{ $response->user_id }}</span>
                                            @else
                                                <span class="text-muted">نامشخص</span>
                                            @endif
                                        </td>
                                        <td>{{ $response->respondent_email ?? 'ندارد' }}</td>
                                        <td>
                                            <span class="text-muted">{{ $response->ip_address }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $response->answers_count }}</span>
                                        </td>
                                        <td>{{ $response->created_at->toJalali()->format(formatJalaliDateTime()) }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.survey.response.show', [$survey->id, $response->id]) }}"
                                                   class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i> مشاهده
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete('{{ route('admin.survey.response.destroy', [$survey->id, $response->id]) }}')">
                                                    <i class="fa fa-trash"></i> حذف
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $responses->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        ScriptLoader::Alert(),
        ScriptLoader::Datepicker(),
    ]])
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.survey.index') }}');
            jalaliDatepicker.startWatch();
        });

        function confirmDelete(url) {
            swal({
                title: 'آیا مطمئن هستید؟',
                text: "این پاسخ به همراه تمام جزئیات آن حذف خواهد شد و قابل بازیابی نیست!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#ff0f3b',
                confirmButtonText: 'بله، حذف کن!',
                cancelButtonText: 'خیر، انصراف',
                closeOnConfirm: false
            }, function () {
                const form = $('#delete-form');
                form.attr('action', url);
                form.submit();
            });
        }
    </script>
@endsection
