@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            \App\Enums\Assets\StyleLoader::Toast(),
            \App\Enums\Assets\StyleLoader::Alert(),
            \App\Enums\Assets\StyleLoader::Dropzone(),
        ]
    ])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.contract.sign.index') }}">درخواست ها</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form id="userSignableForm" class="request-form forms-sample" method="post" action="{{ route('admin.contract.sign.user.update',$userSignable->id) }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.input identify="id" type="hidden" :old="$userSignable->id"/>

                        <x-admin.select-enum identify="status" title="وضغیت امضاء"
                                             :enum-class="\Modules\Contract\app\Enums\UserSignableStatus::class"
                                             :old="$userSignable->status"
                        />

                        <x-admin.textarea identify="note" :rows="6" :old="$userSignable->note" placeholder="توضیحات"/>

                        <x-admin.button title="{{ trans('panel.update') }}"/>

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger" on-click="confirmDelete()"/>

                    </form>


                    <div class="card mt-5 no-shadow-card">
                        <div class="card-header">
                            <h5 class="card-title">بارگذاری پیوست‌ها</h5>
                            <p class="card-text">لطفاً فایل‌های تصویری خود را اینجا بارگذاری کنید. حداکثر اندازه فایل مجاز ۱۰ مگابایت است.</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.contract.attachment.upload') }}" class="dropzone" id="myDropzone">
                            </form>
                        </div>
                    </div>


                    <form id="deleteItem" action="{{ route('admin.contract.sign.destroy',$userSignable->id) }}" method="post" class="form-inline">
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
    \App\Enums\Assets\ScriptLoader::Dropzone(),
    ]])
    @include('admin.partial.request')

    <script>
        $(document).ready(function () {

        })
    </script>
    @include('contract::admin.user_signable.part.script')
@endsection
