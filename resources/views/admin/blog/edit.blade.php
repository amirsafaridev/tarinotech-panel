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
        <h1 class="page-title">بلاگ ها - ویرایش</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">بلاگ ها</a></li>
                <li class="breadcrumb-item active">جدید</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ $routeUpdate }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.input identify="id" :old="$blog->id" type="hidden"/>

                        <x-admin.select-model identify="blog_category_id" title="دسته بندی" :items="$categories" value="title" key="id" :old="$blog->blog_category_id"/>

                        @if($blog->photo)
                            <img class="img img-fluid rounded-2" src="{{ asset($blog->photo) }}" alt="{{ $blog->title }}">
                        @endif
                        <x-admin.input identify="photo" title="تصویر" type="file"/>

                        <x-admin.input identify="title" title="عنوان" :old="$blog->title"/>

                        <x-admin.input identify="slug" title="اسلاگ" :old="$blog->slug"/>

                        <x-admin.textarea identify="body" title="محتوا" :old="$blog->body"/>

                        <x-admin.input identify="meta_description" title="متا - توضیحات" :old="$blog->meta_description"/>

                        <x-admin.input identify="meta_keywords" title="متا - کلمات کلیدی" :old="$blog->meta_keywords"/>

                        <x-admin.checkbox identify="is_publish" :old="$blog->is_publish" description="منتشر شود" />

                        <x-admin.button-submit title="{{ trans('panel.update') }}"/>

                        <x-admin.button-delete/>
                    </form>

                    <form id="deleteItem" action="{{ $routeDestroy }}" method="post" class="form-inline">
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
