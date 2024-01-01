@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[\App\Enums\Assets\StyleLoader::DataTable()]])
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
                        <div>
                            <h5 class="card-title">پروژه طراحی سایت نگین</h5>
                            <h6 class="card-subtitle mb-0 text-muted">example.com</h6>
                        </div>
                        <hr class="hr-message">
                        <div class="aw-message">
                            @for($i=1;$i<=10;$i++)
                                <div class="d-flex  @if($i%2) justify-content-end @else justify-content-start @endif">
                                    <div class="d-flex gap-2 align-items-start aw-message-item  @if($i%2) flex-row-reverse @endif">
                                        <img class="flex-shrink-0 w-7" src="{{ asset('uploads/user.png') }}" alt="">
                                        <div class="aw-message-item-text">
                                            <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از
                                                طراحان گرافیک است. </p>
                                            <spac class="date">1400/02/03 14:20</spac>
                                            <div class="attachment">
                                                <button type="button" href="">
                                                    <span class="fal fa-file"></span>
                                                </button>
                                                <button type="button" href="">
                                                    <span class="fal fa-images"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column gap-2 action">
                                            <button type="button">
                                                <span class="fal fa-edit text-warning"></span>
                                            </button>
                                            <button type="button">
                                                <span class="fal fa-trash text-danger"></span>
                                            </button>
                                            <button type="button">
                                                <span class="fal fa-reply text-info"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                        <hr class="hr-message">
                        <div class="aw-message-action">
                            <div>
                                <textarea placeholder="پیام خود ار بنویسید"></textarea>
                                <div class="attachment">
                                    <div class="d-flex justify-content-between align-items-center item">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fal fa-file text-primary"></span>
                                            <span class="mt-1 font-weight-bold">File Name</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="mt-1 font-weight-bold">10Kb</span>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <span class="fal fa-trash"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="action mt-2">
                                    <button class="btn btn-pill btn-icon btn-gray">
                                        <span class="fal mt-1 fa-message"></span>
                                    </button>
                                    <button class="btn btn-pill btn-icon btn-danger">
                                        <span class="fal mt-1 fa-microphone"></span>
                                    </button>
                                    <button class="btn btn-pill btn-icon btn-info">
                                        <span class="fal mt-1 fa-paperclip"></span>
                                    </button>
                                    <button class="btn btn-pill btn-icon btn-success">
                                        <span class="fal mt-1 fa-send fa-rotate-180"></span>
                                    </button>
                                </div>
                            </div>
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
    @include('admin.partial.loader.script',['load'=>[\App\Enums\Assets\ScriptLoader::DataTable()]])
    @include('support::admin.part.script')
@endsection
