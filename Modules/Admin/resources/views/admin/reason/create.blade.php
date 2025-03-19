@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', [
        'load' => [\App\Enums\Assets\StyleLoader::Toast(), \App\Enums\Assets\StyleLoader::Datepicker()],
    ])
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.reason.index') }}">علت</a>
                </li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post"
                        action="{{ route('admin.admin.reason.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="status">نوع</label>
                            <select name="type" id="type" class="form-control form-control-sm">
                                <option value="0" @if (old('type') == 0) selected @endif>پاداش
                                </option>
                                <option value="1" @if (old('type') == 1) selected @endif>کسورات
                                </option>
                            </select>
                        </div>
                        <x-admin.textarea identify="title" title="علت" />

                        <x-admin.button title="{{ trans('panel.create') }}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    <script>
        $(document).ready(function() {
            activeParentUl('{{ route('admin.admin.reason.index') }}');
        })
    </script>
@endsection
