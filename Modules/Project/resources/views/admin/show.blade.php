@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">پروژه - نمایش</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item active">مدیریت</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            @include('admin.partial.message')
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            @include('project::admin.part.project-info-card')
            @include('project::admin.part.contract-card')
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            @if($project->target_type  === \Modules\Project\app\Models\ProjectWeb::class)
                @include('project::admin.part.web-info-card')
            @endif

            @if($project->target_type  === \Modules\Project\app\Models\ProjectSeo::class)
                @include('project::admin.part.seo-info-card')
            @endif

            @if($project->target_type  === \Modules\Project\app\Models\ProjectAds::class)
                @include('project::admin.part.ads-info-card')
            @endif
        </div>

        @if($project->factors->isNotEmpty())
            <div class="col-12 d-print-none">
                @include('project::admin.part.factor-card')
            </div>
        @endif

    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[]])
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.project.index') }}');
        })
    </script>
@endsection
