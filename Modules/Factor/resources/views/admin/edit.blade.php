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
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.factor.index') }}">فاکتور ها</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <form class="row request-form forms-sample" method="post" action="{{ route('admin.factor.update',$factor->id) }}">
        @method('PUT')
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">ویرایش فاکتور</div>
                </div>

                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <div class="forms-sample">
                        @csrf

                        <x-admin.select-model
                                title="انتخاب پروژه"
                                identify="project_id"
                                key="id"
                                value="optionTitle"
                                :items="$projects"
                                :old="$factor->project_id"
                        />

                        <div id="project_info"></div>

                        <x-admin.input identify="title" title="عنوان فاکتور" :old="$factor->title"/>

                        <x-admin.select-enum identify="status" title="وضعیت"
                                             :enum-class="\App\Enums\Database\Factor\FactorStatus::class"
                                             :old="$factor->status"/>

                        <x-admin.input identify="expired_at"
                                       title="تاریخ انقضاء"
                                       old="{{ verta($factor->expired_at)->format('Y/m/d') }}"
                                       :is-date-picker="true"/>

                        <x-admin.button-submit title="به روز رسانی"/>

                        <x-admin.button-delete/>

                        <button id="btn_add_item" class="btn btn-success" type="button">افزودن آیتم</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="factor_item_container" class="col-12">
            @if($factor->items->isNotEmpty())
                @foreach($factor->items as $index => $item)
                    @include('factor::admin.item.item',compact('item','index','categories'))
                @endforeach
            @endif
        </div>
    </form>

    <form id="deleteItem" action="{{ route('admin.factor.destroy',$factor->id) }}" method="post" class="form-inline">
        @csrf
        @method('DELETE')
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
