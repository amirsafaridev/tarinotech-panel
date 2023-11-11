@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">بلاگ ها - جدید</h1>
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
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ $routeStore }}">
                        @csrf
                        <x-admin.select-model identify="blog_category_id" title="دسته بندی" :items="$categories" value="title" key="id"/>

                        <x-admin.input identify="photo" title="تصویر" type="file"/>

                        <x-admin.input identify="title" title="عنوان"/>

                        <x-admin.textarea identify="body" title="محتوا"/>

                        <x-admin.input identify="meta_description" title="متا - توضیحات"/>

                        <x-admin.input identify="meta_keywords" title="متا - کلمات کلیدی"/>

                        <x-admin.checkbox identify="is_publish" :checked="true" description="منتشر شود" />

                        <x-admin.button-submit/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::CKEditor(),
    ]])
    @include('admin.partial.request')
    @include('admin.partial.ckeditor')
    <script>
        $(document).ready(function () {
            CKEDITOR.replace( 'body');
        })
    </script>
@endsection
