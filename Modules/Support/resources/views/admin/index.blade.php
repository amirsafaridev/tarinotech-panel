@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[\App\Enums\Assets\StyleLoader::DataTable()]])
    <style>
        .aw-chat-height{
            max-height: 800px;
        }
        .aw-chat{
            overflow-y: scroll;
            padding-left: 10px;
        }
        .aw-chat-item{
           margin-bottom: 10px;
            background-color: white;
            overflow: hidden;
            border-radius: 10px;
            border: 1px solid #eeeeee;
        }

        .dark-mode .aw-chat-item{
            background-color: #2a2a4a;
            border: 1px solid #242442;
        }
        .aw-chat-item .aw-chat-header{
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            padding: 10px 14px;
            transition: all 0.2s ease-in-out;
            border-bottom: 1px solid #eeeeee;
        }

        .dark-mode .aw-chat-header{
            border-bottom: 1px solid #201e4e;
        }

        .aw-chat-item .aw-chat-header:hover{
            background-color: #e4e5ff;
        }
        .dark-mode .aw-chat-item .aw-chat-header:hover{
            background-color: #211f53;
        }
        .aw-chat-item .aw-chat-header .aw-project{
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .aw-chat-item .aw-chat-header .aw-project h4{
            margin: 0;
            padding: 0;
            font-weight: 500;
            color: #282f53;
        }
        .dark-mode .aw-chat-item .aw-chat-header .aw-project h4{
            color: #dedefd;
        }

        .aw-chat-item .aw-chat-header .aw-time-notify{
            display: flex;
            flex-direction: column;
            align-items: end;
            gap: 8px;
        }
        .aw-chat-item .aw-chat-header .aw-time-notify .notify{
            width: 25px;
            height: 25px;
            background-color: #c11a1a;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 25px;
            font-size: 12px;
            line-height: 0;
            color: white;
        }


        .aw-chat-item  .aw-chat-user{
            display: flex;
            justify-content: space-between;
            padding: 10px 14px;
        }
        .aw-chat-item  .aw-chat-user .aw-user{
            display: flex;
            gap: 5px;
            flex-grow: 1;
            flex-wrap: wrap;
        }
        .aw-chat-item  .aw-chat-user .aw-user img{
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }
        .aw-chat-item  .aw-chat-user .aw-action{
            flex-shrink: 0;
        }
        .aw-chat-item  .aw-chat-user .aw-action button{
            border-radius: 50%;
            border: 0;
            appearance: none;
            width: 25px;
            height: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .aw-chat-item  .aw-chat-user .aw-action button.aw-action-success{
            background-color: #1b9783;
            color: white;
        }


        .aw-message{
            height: 600px;
            overflow-y: scroll;
            padding-left: 20px;

        }
        .aw-message .aw-message-item{
            margin-bottom: 20px;
        }
        .aw-message .aw-message-item img{
            border-radius: 50%;
        }
        .aw-message .aw-message-item .aw-message-item-text{
            background-color: #f9f9f9;
            padding: 10px 14px;
            border-radius: 5px;
            width: 90%;
        }
        .dark-mode .aw-message .aw-message-item .aw-message-item-text{
            background-color: #2d2d4e;
        }
        .aw-message .aw-message-item .aw-message-item-text p{
            margin: 0;
            padding: 0;
        }
        .aw-message .aw-message-item .aw-message-item-text .date{
            font-size: 12px;
        }

        .aw-message .aw-message-item .aw-message-item-text .attachment button{
            display: inline-flex;
            justify-content: center;
            align-items: center;
            appearance: none;
            border: 0;
            padding: 5px;
            width: 30px;
            border-radius: 5px;
            background-color: #6c5ffc;
            color: white;
        }
        .aw-message .aw-message-item .action button{
            border: 0;
            width: 25px;
            height: 25px;
            font-size: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
        }

        .dark-mode .aw-message .aw-message-item .action button{
            background-color:#2d2d4e;
        }
        .hr-message{
            background-color: #959595;
            margin: 20px -25px;
        }
        .aw-message-action textarea{
            width: 100%;
            resize: none;
            padding: 10px 10px 40px 10px;
            height: 150px;
            border: 0;
        }

        .dark-mode .aw-message-action textarea{
            background-color:#2d2d4e;
            color: white;
        }

        .aw-message-action .action button{
            padding: 5px;
            width: 50px;
            border-radius: 5px;
        }

        .aw-message-action .attachment{
            padding: 8px 10px;
            background-color: white;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        }
        .dark-mode .aw-message-action .attachment{
            background-color:#2d2d4e;
            box-shadow: rgb(34, 34, 62) 0px 2px 8px 0px;
        }

    </style>
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
                                            <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. </p>
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
                                            <button  type="button">
                                                <span class="fal fa-edit text-warning"></span>
                                            </button>
                                            <button  type="button">
                                                <span class="fal fa-trash text-danger"></span>
                                            </button>
                                            <button  type="button">
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
            <div class="aw-chat aw-chat-height">
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <div class="flex-grow-1">
                        <x-admin.input identify="search" placeholder="جستجو"/>
                    </div>
                    <a href="" class="btn btn-success">ایجاد گروه</a>
                </div>
                <div class="">
                    @for($i=0;$i<10;$i++)
                        <div class="aw-chat-item">
                            <div class="aw-chat-header">
                                <div class="aw-project">
                                    <h4>پروژه طراحی سایت نگین</h4>
                                    <span>example.com</span>
                                </div>
                                <div class="aw-time-notify">
                                    <span  class="notify">1</span>
                                    <span>1400/05/03 14:18</span>
                                </div>
                            </div>

                            <div class="aw-chat-user">
                                <div class="aw-user">
                                    @for($j=0;$j<rand(2,10);$j++)
                                        <img src="{{ asset('uploads/user.png') }}"/>
                                    @endfor
                                </div>
                                <div class="aw-action">
                                    <button class="aw-action-success">
                                        <span class="fal fa-plus"></span>
                                    </button>

                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[\App\Enums\Assets\ScriptLoader::DataTable()]])
@endsection
