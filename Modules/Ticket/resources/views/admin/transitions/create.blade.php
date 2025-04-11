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
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.transitions.index') }}">انتقال وضعیت‌های تیکت</a></li>
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
                            <label for="from_status_id" class="form-label">وضعیت شروع:</label>
                            <select name="from_status_id" id="from_status_id" class="form-control @error('from_status_id') is-invalid @enderror">
                                <option value="">انتخاب کنید</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ old('from_status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('from_status_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="to_status_id" class="form-label">وضعیت پایان:</label>
                            <select name="to_status_id" id="to_status_id" class="form-control @error('to_status_id') is-invalid @enderror">
                                <option value="">انتخاب کنید</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ old('to_status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('to_status_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <x-admin.input identify="days_until_transition" title="تعداد روز تا انتقال" type="number" :old="old('days_until_transition', 0)"/>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">فعال</label>
                            </div>
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