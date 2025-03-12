@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', [
        'load' => [\App\Enums\Assets\StyleLoader::Toast(),
         \App\Enums\Assets\StyleLoader::Datepicker(),
         \App\Enums\Assets\StyleLoader::Select2(),

        ],
    ])
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.personnel-salary.index') }}">تنخواه</a></li>
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
                        action="{{ route('admin.admin.personnel-salary.store') }}">
                        @csrf

                        <x-admin.input identify="price" title="مبلغ" />
                        <x-admin.select-model title="انتخاب پرسنل" identify="user_id" key="id" :items="$users"
                            value="fullName" />
                            <x-admin.input
                            identify="date"
                            title="تاریخ"
                            :is-date-picker="true"
                            :old="request('date')"
                    />
                        <x-admin.textarea identify="description" title="توضیحات" />

                        <x-admin.button title="{{ trans('panel.create') }}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@include('admin.partial.loader.script',['load'=>[
    \App\Enums\Assets\ScriptLoader::Datepicker(),
    \App\Enums\Assets\ScriptLoader::Select2(),

]])
    @include('admin.partial.request')
    @include('admin.partial.script.global')

    <script>
        $(document).ready(function() {
            jalaliDatepicker.startWatch();
            makeInputPrice($('#price'));

            activeParentUl('{{ route('admin.admin.personnel-salary.index') }}');
        })
    </script>
@endsection
