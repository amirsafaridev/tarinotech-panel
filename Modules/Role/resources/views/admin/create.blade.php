@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',[
       'load'=>[\App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::MultiSelect()
       ]
   ])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.role.index') }}">نقش ها</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.role.store') }}">
                        @csrf

                        <x-admin.input identify="name" title="نام نقش"/>

                        <x-admin.select-permission class="multiple"
                                                   identify="permissions[]"
                                                   title="دسترسی ها"
                                                   :items="$permissions"
                                                   key="id"
                                                   value="title"
                                                   multiple="multiple"/>

                        <div class="d-flex align-items-center gap-10 my-3">
                            <button id="btn-select" class="btn btn-sm btn-outline-info"
                                    type="button">{{ trans('fields.role.select_all') }}</button>
                            <button id="btn-de-select" class="btn btn-sm btn-outline-success mx-2"
                                    type="button">{{ trans('fields.role.remove_selected') }}</button>
                        </div>

                        <x-admin.button-submit/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',[
        'load'=>[\App\Enums\Assets\ScriptLoader::MultiSelect()],
    ])
    @include('admin.partial.request')
    @include('role::admin.script')
@endsection
