@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            \App\Enums\Assets\StyleLoader::Toast(),
            \App\Enums\Assets\StyleLoader::Alert(),
            \App\Enums\Assets\StyleLoader::Select2(),
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
        <div class="col-md-8">
            <x-admin.chat :chat="$chat" :has-end-button="true"/>
        </div>
        <div class="col-md-4">
            @include('ticket::admin.part.ticket-info-card', [
                'chat' => $chat, 
                'ticketDetail' => $ticketDetail,
                'showEditButton' => true,
                'showRating' => true
            ])
            
            @include('ticket::admin.part.reassign-ticket', [
                'chat' => $chat, 
                'ticketDetail' => $ticketDetail,
                'availableAdmins' => $availableAdmins
            ])
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    @include('admin.partial.request')

    <script>
        $(document).ready(function () {
            setupSocketChat(parseInt('{{ $chat->id }}'));
            $('.select2').select2();
        });
    </script>
@endsection
