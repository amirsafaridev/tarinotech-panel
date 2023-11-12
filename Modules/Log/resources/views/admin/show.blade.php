@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            \App\Enums\Assets\StyleLoader::Toast(),
            \App\Enums\Assets\StyleLoader::Alert(),
        ]
    ])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.log.index') }}">لاگ ها</a></li>
                <li class="breadcrumb-item active">نمایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-12 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <td width="15%">شناسه</td>
                                <td>{{ $activity->id }}</td>
                            </tr>

                            <tr>
                                <td>نام کانال</td>
                                <td>{{ getEventName($activity->log_name) }}</td>
                            </tr>

                            <tr>
                                <td>رویداد</td>
                                <td>{!! getEventType($activity->event) !!}</td>
                            </tr>

                            <tr>
                                <td>نوع کاربر</td>
                                <td>{{ $activity->causer ? getEventCauserType($activity->causer_type) : '-'}}</td>
                            </tr>

                            <tr>
                                <td>پروفایل</td>
                                <td>{!!  $activity->causer ?  getCauserProfile($activity->causer) : '-' !!}</td>
                            </tr>


                            @if($activity->properties->isNotEmpty())
                                <tr>
                                    <td>تغییرات</td>
                                    <td>
                                        @if(isset($activity->properties['old']) && isset($activity->properties['attributes']))
                                            @component('log::admin.render.update-properties', ['oldProperties' => $activity->properties['old'] ?? [], 'newProperties' => $activity->properties['attributes']])
                                            @endcomponent

                                        @elseif(isset($activity->properties['old']))
                                            @component('log::admin.render.delete-properties', ['oldProperties' => $activity->properties['old']])
                                            @endcomponent
                                        @elseif(isset($activity->properties['attributes']))
                                            @component('log::admin.render.create-properties', ['newProperties' => $activity->properties['attributes']])
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endif


                            <tr>
                                <td>تاریخ ایجاد</td>
                                <td>{{ verta($activity->created_at)->format(formatJalaliDateTime()) }}</td>
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
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.log.index') }}');
        })
    </script>
@endsection
