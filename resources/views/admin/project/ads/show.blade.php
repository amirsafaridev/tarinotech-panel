@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">پروژه گوگل ادز - نمایش</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.ads.index') }}">گوگل ادز</a></li>
                <li class="breadcrumb-item active">نمایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">عنوان کادر</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <p>{{ $project->title }}</p>
                    <p>{{ $project->domain }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">عنوان کادر</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <p>اماده جایگزاری</p>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[]])
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.project.seo.index') }}');
        })
    </script>
@endsection
