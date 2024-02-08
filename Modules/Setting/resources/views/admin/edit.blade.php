@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',[
        'load'=>[
            \App\Enums\Assets\StyleLoader::Toast(),
        ]
    ])
@endsection
@php
    use \App\Enums\Database\Setting\SettingItems;
@endphp
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.setting.update') }}">
                        @csrf
                        @method('PATCH')
                        @foreach(SettingItems::asArray() as $item)
                            @if(in_array($item,[SettingItems::DIRECT_CONFIRM_FACTOR,SettingItems::DIRECT_CONFIRM_PROJECT]))
                                <x-admin.checkbox :identify="$item" :description="SettingItems::getDescription($item)" :old="$settings[$item]"/>
                            @else
                                <x-admin.input :identify="$item" :title="SettingItems::getDescription($item)" :old="$settings[$item]"/>
                            @endif
                        @endforeach
                        <x-admin.button-submit title="{{ trans('panel.update') }}"/>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
@endsection
