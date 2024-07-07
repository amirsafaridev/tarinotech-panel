@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            \App\Enums\Assets\StyleLoader::Toast(),
            \App\Enums\Assets\StyleLoader::Alert(),
            \App\Enums\Assets\StyleLoader::Datepicker(),
        ]
    ])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.content.slider.index') }}">اسلایدر ها</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.content.slider.update',$slider->id) }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.input identify="id" type="hidden" :old="$slider->id"/>

                        <x-admin.input identify="title" title="عنوان" :old="$slider->title"/>

                        <x-admin.input identify="link" title="لینک"/>

                        @if($slider->photo)
                            <img class="img img-fluid rounded-2" src="{{ asset($slider->photo) }}" alt="{{ $slider->title }}">
                        @endif
                        <x-admin.input type="file" identify="photo" title="تصویر"/>

                        <x-admin.input identify="published_at"
                                       title="تاریخ انتشار"
                                       :old="verta($slider->published_at)->format('Y/m/d')"
                                       :is-date-picker="true"/>

                        <x-admin.input identify="archived_at"
                                       title="تاریخ آرشیو"
                                       :old="verta($slider->archived_at)->format('Y/m/d')"
                                       :is-date-picker="true"/>

                        <x-admin.checkbox identify="status" :old="$slider->status" description="منتشر شود" />

                        <x-admin.textarea identify="description" title="متن" :old="$slider->description"/>

                        <x-admin.button title="{{ trans('panel.update') }}"/>

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger" on-click="confirmDelete()"/>
                    </form>

                    <form id="deleteItem" action="{{ route('admin.content.slider.destroy',$slider->id) }}" method="post" class="form-inline">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',[
        'load'=>[
            \App\Enums\Assets\ScriptLoader::Alert(),
            \App\Enums\Assets\ScriptLoader::Datepicker(),
        ],
    ])
    @include('admin.partial.request')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.content.slider.index') }}');
        })
    </script>
    @include('content::admin.slider.script')

@endsection
