@php use App\Enums\Assets\StyleLoader; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            StyleLoader::Toast(),
            StyleLoader::Alert(),
        ]
    ])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.subjects.index') }}">موضوعات تیکت</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post"
                          action="{{ route('admin.ticket.subjects.update', $ticketSubject->id) }}">
                        @csrf
                        @method('put')

                        <x-admin.input identify="title" title="عنوان موضوع" :old="$ticketSubject->title"/>

                        <x-admin.checkbox identify="is_published"
                                          description="منتشر شود؟"
                                          :old="old('is_published', $ticketSubject->is_published)"/>

                        <x-admin.button title="{{ trans('panel.update') }}"/>

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger"
                                        on-click="confirmDelete()"/>
                    </form>

                    <form id="deleteItem" action="{{ route('admin.ticket.subjects.destroy', $ticketSubject) }}"
                          method="post" class="form-inline">
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
            ScriptLoader::Alert(),
        ],
    ])
    @include('admin.partial.request')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.ticket.subjects.index') }}');
        });
    </script>
@endsection
