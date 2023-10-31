@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[\App\Enums\Assets\StyleLoader::DataTable()]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">امکانات جانبی</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">{{ $project->title }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.'.getRouteProjectType($project->project_base_id).'.show',$project->id) }}">پروژه ها</a></li>
                <li class="breadcrumb-item active">امکانات جانبی</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $project->title }} | {{ $project->domain }}</h3>
                    <a class="btn btn-success btn-sm" href="{{ route('admin.project.facility.create',1) }}">ایجاد</a>
                </div>

                <div class="card-body">
                    @include('admin.partial.message')
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
