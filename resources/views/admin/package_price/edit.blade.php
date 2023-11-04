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
        <h1 class="page-title">پکیج ها</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.package.index') }}">پکیج ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.package.edit',$package->id) }}">{{ $package->title }}</a></li>
                <li class="breadcrumb-item active">ویرایش مبلغ</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ $routeUpdate }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.input identify="title" title="تاریخ شروع" :disabled="true" :old="verta($packagePrice->start_at)->format(formatJalaliDate())"/>

                        <x-admin.input identify="title" title="تاریخ پایان" :disabled="true" :old="!$packagePrice->end_at ? 'تا هم اکنون' : verta($packagePrice->end_at)->format(formatJalaliDate())"/>

                        <x-admin.input identify="price" title="قیمت" :old="$packagePrice->price"/>

                        <x-admin.button-submit title="{{ trans('panel.update') }}"/>
                        <x-admin.button-delete/>

                    </form>

                    <form id="deleteItem" action="{{ $routeDestroy }}" method="post" class="form-inline">
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
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function (){
            makeInputPrice($('#price'));
        })
    </script>

@endsection
