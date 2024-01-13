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
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.permission.index') }}">پرمیشن ها</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.permission.update',$permission->id) }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.input identify="id" type="hidden" :old="$permission->id"/>

                        <x-admin.input identify="name" title="نام" :old="$permission->name"/>

                        <x-admin.input identify="title" title="عنوان" :old="$permission->title"/>

                        <x-admin.button-submit title="{{ trans('panel.update') }}"/>

                        <x-admin.button-delete/>
                    </form>

                    <form id="deleteItem" action="{{ route('admin.permission.destroy',$permission->id) }}" method="post" class="form-inline">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
    \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::CKEditor(),
    ]])
    @include('admin.partial.request')
    @include('admin.partial.ckeditor')

    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.blog.index') }}');
            CKEDITOR.replace( 'body');
        })
    </script>
@endsection
