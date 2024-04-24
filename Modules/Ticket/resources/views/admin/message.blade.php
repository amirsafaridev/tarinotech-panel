@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            \App\Enums\Assets\StyleLoader::Toast(),
            \App\Enums\Assets\StyleLoader::Alert(),
        ]
    ])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.index') }}">تیکت ها</a></li>
                <li class="breadcrumb-item active">گفت گو</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <x-admin.chat :chat="$chat"/>

        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
    \App\Enums\Assets\ScriptLoader::Alert(),

    ]])

    <script>
        $(document).ready(function () {

        })
    </script>
@endsection
