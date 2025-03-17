@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', ['load' => [\App\Enums\Assets\StyleLoader::DataTable()]])
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
@include('admin.partial.loader.script', ['load' => [\App\Enums\Assets\ScriptLoader::DataTable()]])
@include('admin.partial.datatable_offline')

    
@endsection
