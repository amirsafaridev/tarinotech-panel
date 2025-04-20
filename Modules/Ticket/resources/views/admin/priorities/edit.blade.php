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
                <li class="breadcrumb-item"><a href="{{ route('admin.ticket.priorities.index') }}">اولویت‌های تیکت</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.ticket.priorities.update', $ticketPriority->id) }}">
                        @csrf
                        @method('put')

                        <x-admin.input identify="name" title="نام اولویت" :old="$ticketPriority->name"/>

                        <div class="mb-3">
                            <label for="color" class="form-label">رنگ</label>
                            <input type="color"
                                   class="form-control"
                                   name="color"
                                   id="color"
                                   value="{{ old('color', $ticketPriority->color) }}" />
                        </div>

                        <x-admin.input identify="level" title="سطح اولویت" type="number" :old="old('level', $ticketPriority->level)"/>

                        <x-admin.checkbox identify="should_notify"
                                      description="اطلاع رسانی"
                                      :old="old('should_notify', $ticketPriority->should_notify)"/>

                        <x-admin.textarea identify="description" title="توضیحات" :old="$ticketPriority->description"/>

                        <x-admin.button title="{{ trans('panel.update') }}"/>

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger" on-click="confirmDelete()"/>
                    </form>

                    <form id="deleteItem" action="{{ route('admin.ticket.priorities.destroy', $ticketPriority) }}" method="post" class="form-inline">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',[
        'load'=>[
            \App\Enums\Assets\ScriptLoader::Alert(),
        ],
    ])
    @include('admin.partial.request')
    <script>
        $(document).ready(function (){
            activeParentUl('{{ route('admin.ticket.priorities.index') }}');
        });
    </script>
@endsection
