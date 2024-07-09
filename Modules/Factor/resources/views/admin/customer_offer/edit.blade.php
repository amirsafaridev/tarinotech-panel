@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::Alert(),
       \App\Enums\Assets\StyleLoader::Select2(),
       \App\Enums\Assets\StyleLoader::Datepicker(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.factor.customer-offer.index') }}">فاکتور ها (تایید  آفر مشتریان)</a></li>
                <li class="breadcrumb-item active">تایید</li>
            </ol>
        </div>
    </div>

    <form class="row request-form forms-sample" method="post" action="{{ route('admin.factor.customer-offer.update',$factor->id) }}">
        @method('PATCH')
        <div class="col-xl-3 col-lg-9 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">تایید فاکتور</div>
                </div>

                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <div class="forms-sample">
                        @csrf

                        <x-admin.checkbox identify="is_confirm" :old="$factor->is_confirm" description="تایید فاکتور"/>

                        <x-admin.button title="به روز رسانی"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-3 col-md-6 col-12">
            @include('factor::admin.part.card-info',['factor' => $factor])

            @if($factor->items->isNotEmpty())
                @include('factor::admin.part.card-items',['items' => $factor->items])
            @endif
        </div>
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Select2(),

    ]])
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('factor::admin.part.script')
@endsection
