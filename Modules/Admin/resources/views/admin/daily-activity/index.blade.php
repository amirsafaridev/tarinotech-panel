@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
@include('admin.partial.loader.style',['load'=>[
    \App\Enums\Assets\StyleLoader::Toast(),
    \App\Enums\Assets\StyleLoader::Alert(),
    \App\Enums\Assets\StyleLoader::Datepicker(),
    \App\Enums\Assets\StyleLoader::DataTable()

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

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">درخواست‌های ویرایش ساعت کاری</h3>
                </div>

                <div class="card-body">
                    @include('admin.partial.message')

                    <div class="table-responsive">
                        <table id="data-table" class="table table-bordered text-nowrap">
                            <thead>
                                <tr>
                                    <th>نام کاربر</th>
                                    <th>تاریخ</th>
                                    <th>زمان فعلی شروع</th>
                                    <th>زمان فعلی پایان</th>
                                    <th>زمان درخواستی شروع</th>
                                    <th>زمان درخواستی پایان</th>
                                    <th>دلیل درخواست</th>
                                    <th>تاریخ درخواست</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($activities->isNotEmpty())
                                @foreach ($activities as $activity)
                                    <tr>
                                        <td>{{ $activity->user->fullname }}</td>
                                        <td>{{ Verta::instance($activity->date)->format('Y/m/d') }}</td>
                                        <td>{{ $activity->start_time ? Verta::instance($activity->start_time)->format('H:i:s') : '-' }}</td>
                                        <td>{{ $activity->end_time ? Verta::instance($activity->end_time)->format('H:i:s') : '-' }}</td>
                                        <td>{{ $activity->edit_request_data['requested_start_time'] }}</td>
                                        <td>{{ $activity->edit_request_data['requested_end_time'] }}</td>
                                        <td>{{ $activity->edit_request_data['reason'] }}</td>
                                        <td>{{ Verta::instance($activity->edit_request_data['requested_at'])->format('Y/m/d H:i:s') }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-success approve-btn"
                                                href="{{ route('admin.admin.daily-activity.approve', $activity->id) }}">{{ __('panel.action.approve') }}</a>
                                            <button type="button" class="btn btn-sm btn-danger reject-btn"
                                                data-activity-id="{{ $activity->id }}" data-bs-toggle="modal"
                                                data-bs-target="#rejectModal-{{ $activity->id }}">
                                                رد
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal-{{ $activity->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">رد درخواست ویرایش</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form class="request-form forms-sample" method="post"
                                                    action="{{ route('admin.admin.daily-activity.reject', $activity->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <x-admin.textarea
                                                            title="دلیل رد درخواست"
                                                            identify="reject_reason" rows="3" />
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">انصراف</button>
                                                        <x-admin.button title="ثبت" />
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('admin.partial.loader.script', ['load' => [
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::DataTable()

    ]])
        @include('admin.partial.datatable_offline')

    <script>
        $(document).ready(function() {
            activeParentUl('{{ route('admin.admin.daily-activity.index') }}');
        });
    </script>
@endsection
