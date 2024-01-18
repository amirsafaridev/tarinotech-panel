@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Toast(),
        \App\Enums\Assets\StyleLoader::Alert(),
    ]])
    <link rel="stylesheet" href="{{ asset('res-admin/assets/css/chat.css') }}">
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <x-admin.chat/>
        </div>
        <div class="col-md-5">
            <div class="aw-chat aw-chat-height" id="chat-container">
                <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                    <div class="flex-grow-1">
                        <x-admin.input identify="search" placeholder="جستجو"/>
                    </div>
                    @can('ADMIN_SUPPORT_GROUP_CREATE')
                        <a href="{{ route('admin.support.group.create') }}" class="btn btn-success">ایجاد گروه</a>
                    @endcan
                </div>
                <div class="" id="chat-group-container">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
