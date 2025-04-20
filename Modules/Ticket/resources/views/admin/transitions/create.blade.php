@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.transitions.index') }}">انتقال وضعیت‌ها</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.ticket.transitions.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="from_status_id">وضعیت مبدا</label>
                            <select class="form-select" name="from_status_id" id="from_status_id">
                                <option value="">انتخاب کنید</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ old('from_status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="to_status_id">وضعیت مقصد</label>
                            <select class="form-select" name="to_status_id" id="to_status_id">
                                <option value="">انتخاب کنید</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ old('to_status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="event_id">رویداد</label>
                            <select class="form-select" name="event_id" id="event_id">
                                <option value="">انتخاب کنید</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="days_trigger">تعداد روز (اختیاری)</label>
                            <input type="number" class="form-control" name="days_trigger" id="days_trigger" min="0" value="{{ old('days_trigger') }}">
                            <div class="form-text">در صورتی که رویداد زمانی است، تعداد روزهای مورد نیاز را وارد کنید</div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">فعال</label>
                        </div>

                        <x-admin.button title="{{ trans('panel.create') }}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    <script>
        $(document).ready(function (){
            activeParentUl('{{ route('admin.ticket.transitions.index') }}');
        });
    </script>
@endsection