@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::DataTable(),
        \App\Enums\Assets\StyleLoader::Toast(),
    ]])
    <link rel="stylesheet" href="{{ asset('res-admin/assets/css/chat.css') }}">
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center" id="message-header-container">
                            <img class="w-7 h-7 rounded m-logo d-none" src="" alt="">
                            <div class="ms-3">
                               <h5 class="card-title m-title">یک گفتگو را انتخاب کنید</h5>
                               <h6 class="card-subtitle mb-0 text-muted m-description"></h6>
                            </div>
                        </div>
                        <hr class="hr-message">
                        <div class="aw-message" id="message-container">

                        </div>
                        <hr class="hr-message">
                        <div class="aw-message-action">
                            <form method="post" id="message-form" action="{{ route('admin.chat.message.store') }}">
                                @csrf
                                <x-admin.input identify="chat_id" type="hidden"/>
                                <x-admin.input identify="parent_id" type="hidden"/>

                                <div id="replay-container">

                                </div>

                                <div class="position-relative">
                                    <span class="position-absolute top-100 start-50 translate-middle">
                                        <button class="btn btn-sm btn-danger d-none" id="btn-cancel-edit" type="button">انصراف</button>
                                    </span>
                                    <x-admin.textarea identify="message" placeholder="پیام خود ار بنویسید" />
                                </div>
                                <div class="attachment" id="attachment-container">
                                </div>
                                <div>
                                    <x-admin.input type="file" identify="file-attachment" id="file-attachment"/>
                                </div>
                                <div class="action mt-2">
                                    <button type="button" class="btn btn-pill btn-icon btn-gray">
                                        <span class="fal mt-1 fa-message"></span>
                                    </button>
                                    <button type="button" id="btn-microphone" class="btn btn-pill btn-icon btn-danger">
                                        <span class="fal mt-1 fa-microphone"></span>
                                    </button>
                                    <button type="submit" id="btn-message-send" class="btn btn-pill btn-icon btn-success">
                                        <span class="fal mt-1 fa-send fa-rotate-180"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="aw-chat aw-chat-height" id="chat-container">
                <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                    <div class="flex-grow-1">
                        <x-admin.input identify="search" placeholder="جستجو"/>
                    </div>
                    <a href="{{ route('admin.support.group.create') }}" class="btn btn-success">ایجاد گروه</a>
                </div>
                <div class="" id="chat-group-container">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::DataTable(),
        \App\Enums\Assets\ScriptLoader::Toast(),
        \App\Enums\Assets\ScriptLoader::AjaxForm(),
        \App\Enums\Assets\ScriptLoader::Recorder(),
    ]])
    @include('support::admin.part.script')
@endsection
