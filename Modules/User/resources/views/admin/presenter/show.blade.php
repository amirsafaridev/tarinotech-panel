@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')

@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.presenter.index') }}">نمایندگان</a></li>
                <li class="breadcrumb-item active">نمایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            @include('user::admin.presenter.card.info')
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            @include('user::admin.presenter.card.project',['projects' => $user->accessProjects])
        </div>
    </div>
@endsection
@section('script')

@endsection
