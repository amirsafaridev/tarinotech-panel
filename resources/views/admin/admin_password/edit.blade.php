@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Toast(),
    ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ trans('panel.admin.title') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.index') }}">{{ trans('panel.admin.title') }}</a></li>
                <li class="breadcrumb-item active">{{ trans('panel.admin.edit_password') }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" enctype="multipart/form-data" method="post" action="{{ $routeUpdate }}">
                        @csrf
                        @method('PATCH')



                        <x-admin.input identify="password" :title="trans('fields.admin.password')" type="password" />

                        <x-admin.input identify="password_rep" :title="trans('fields.admin.password_rep')" type="password" />


                        <x-admin.button-submit title="{{ trans('panel.update') }}"/>


                    </form>


                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('admin.partial.loader.script',['load'=>[
    ]])
    @include('admin.partial.ckeditor')
    <script>
        $(document).ready(function () {

        })
    </script>
@endsection
