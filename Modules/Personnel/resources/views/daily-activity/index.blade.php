@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
@include('admin.partial.loader.style',['load'=>[
    \App\Enums\Assets\StyleLoader::Toast(),
    \App\Enums\Assets\StyleLoader::Alert(),
    \App\Enums\Assets\StyleLoader::Datepicker(),
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">فعالیت روزانه
                    </h3>

                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                                <tr>

                                    <th>تاریخ</th>
                                    <th>شروع</th>
                                    <th>پایان</th>
                                    <th>مدت زمان</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if ($activities->isNotEmpty())
                                    @foreach ($activities as $activity)
                                        <tr class="{{ $activity->status === 'incorrect_entry' ? 'table-danger' : '' }}">
                                            <td>{{ Verta::instance($activity->date)->format('Y/m/d') }}</td>
                                            <td>{{ $activity->start_time ? Verta::instance($activity->start_time)->format('H:i:s') : '-' }}
                                            </td>
                                            <td>{{ $activity->end_time ? Verta::instance($activity->end_time)->format('H:i:s') : '-' }}
                                            </td>
                                            <td>{{ $activity->total_duration ? gmdate('H:i:s', $activity->total_duration) : '-' }}
                                            </td>
                                            <td>
                                                @switch($activity->status)
                                                    @case('active')
                                                        <span class="badge bg-success">فعال</span>
                                                    @break

                                                    @case('inactive')
                                                        <span class="badge bg-secondary">اتمام</span>
                                                    @break

                                                    @case('incorrect_entry')
                                                        <span class="badge bg-danger">عدم درج صحیح</span>
                                                    @break

                                                    @case('absent')
                                                        <span class="badge bg-warning">غایب</span>
                                                    @break
                                                    @case('reject')
                                                    <span class="badge bg-danger">رد شده</span>
                                                @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @if ($activity->canRequestEdit())
                                                    <button type="button" class="btn btn-sm btn-primary edit-request-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editRequestModal-{{ $activity->id }}">
                                                        درخواست ویرایش
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        <!-- Modal درخواست ویرایش -->
                                        <div class="modal fade" id="editRequestModal-{{ $activity->id }}" tabindex="-1"
                                            aria-labelledby="editRequestModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editRequestModalLabel">درخواست ویرایش
                                                            فعالیت</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form class="request-form forms-sample" method="post" action="{{ route('admin.personnel.daily-activity.request-edit', $activity->id) }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">تاریخ فعالیت</label>
                                                                <p class="form-control-static">{{ Verta::instance($activity->date)->format('Y/m/d') }}</p>
                                                            </div>

                                                            <x-admin.input type="time" identify="start_time" title="ساعت شروع" value="{{ $activity->start_time ? Verta::instance($activity->start_time)->format('H:i:s') : '' }}" />
                                                            <x-admin.input type="time" identify="end_time" title="ساعت پایان" value="{{ $activity->end_time ? Verta::instance($activity->end_time)->format('H:i:s') : '' }}" />
                                                            <x-admin.textarea identify="reason" title="دلیل درخواست" rows="3"/>
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
    ]])
    <script>
        $(document).ready(function() {
            activeParentUl('{{ route('admin.personnel.daily-activity.index') }}');
        });
    </script>
@endsection
