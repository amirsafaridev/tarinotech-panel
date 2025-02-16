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
                <li class="breadcrumb-item"><a href="{{ route('admin.support.index') }}">پشتیبانی</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.support.group.update',$chat->id) }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.select-model
                                title="انتخاب پروژه"
                                identify="project_id"
                                key="id"
                                value="optionTitle"
                                :old="$chat->project_id"
                                :items="$projects"
                        />

                        <x-admin.select-model
                                title="انتخاب پرسنل"
                                identify="admin_id[]"
                                value="optionTitle"
                                key="id"
                                :old="$oldUsers"
                                :multiple="true"
                                :items="$admins"
                        />

                        @if($chat->logo)
                            <img class="img img-fluid rounded-2 w-20" src="{{ asset($chat->logo) }}" alt="{{ $chat->title }}">
                        @endif
                        <x-admin.input identify="logo" title="تصویر" type="file"/>

                        <x-admin.input identify="title" title="عنوان" :old="$chat->title"/>

                        <x-admin.select-enum  title="وضعیت گروه"
                                              identify="status"
                                              :old="$chat->status"
                                              :enum-class="\App\Enums\Database\Chat\ChatStatus::class"/>

                        <x-admin.button title="{{ trans('panel.update') }}"/>

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger" on-click="confirmDelete()"/>
                    </form>

                    <form id="deleteItem" action="{{ route('admin.support.group.destroy',$chat->id) }}" method="post" class="form-inline">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    @include('admin.partial.request')
    @include('admin.partial.ckeditor')


    <script>
        const projectId = $('#project_id');
        const adminIds = $('#admin_id');
        $(document).ready(function () {
            activeParentUl('{{ route('admin.support.index') }}');
            projectId.select2();
            adminIds.select2();
        })
    </script>
@endsection
