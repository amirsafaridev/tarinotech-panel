@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
@include('admin.partial.loader.style',['load'=>[
    \App\Enums\Assets\StyleLoader::Toast(),
    \App\Enums\Assets\StyleLoader::Alert(),
    \App\Enums\Assets\StyleLoader::Datepicker(),
    \App\Enums\Assets\StyleLoader::Select2(),


]])
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.bonuses.index') }}">پاداش</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post"
                        action="{{ route('admin.admin.bonuses.update', $bonusesDeduction->id) }}">
                        @csrf
                        @method('PATCH')
                        <x-admin.input identify="price" title="مبلغ" :old="$bonusesDeduction->price" />
                        <x-admin.select-model title="انتخاب پرسنل" identify="user_id" key="id":items="$users"
                        value="fullName" :old="$bonusesDeduction->user_id" />
                            <x-admin.select-model title="علت" identify="reason_id" key="id" value="title"
                            :items="$reasons" :old="$bonusesDeduction->reason->id" />
                            <x-admin.input
                            identify="from_date"
                            title="تاریخ"
                            :is-date-picker="true"
                            :old="$bonusesDeduction->date"
                    />
                        <x-admin.textarea identify="description" title="توضیحات" :old="$bonusesDeduction->description" />

                        <x-admin.button title="ویرایش" />

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger"
                            on-click="confirmDelete()" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteItem" action="{{ route('admin.admin.bonuses.destroy', $bonusesDeduction->id) }}" method="post"
        class="form-inline">
        @csrf
        @method('DELETE')
    </form>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('admin.partial.script.global')

    @include('admin.partial.loader.script', ['load' => [
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Select2(),

        ]])
    <script>
        $(document).ready(function() {
            jalaliDatepicker.startWatch();

            activeParentUl('{{ route('admin.admin.bonuses.index') }}');
        })
    </script>
@endsection
