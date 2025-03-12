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
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.personnel-assistance.index') }}">مساعده</a></li>
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
                        action="{{ route('admin.admin.personnel-assistance.update', $personnelAssistance->id) }}">
                        @csrf
                        @method('PATCH')
                        <x-admin.input identify="price" title="مبلغ" :old="number_format($personnelAssistance->price)" />
                        <x-admin.select-model title="انتخاب پرسنل" identify="user_id" key="id"
                        :old="$personnelAssistance->user_id"
                        :items="$users"
                        value="fullName"  />
                            <x-admin.input
                            identify="date"
                            title="تاریخ"
                            :is-date-picker="true"
                            :old="$personnelAssistance->date"
                        />
                        <x-admin.textarea identify="description" title="توضیحات" :old="$personnelAssistance->description" />

                        <x-admin.button title="ویرایش" />

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger"
                            on-click="confirmDelete()" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteItem" action="{{ route('admin.admin.personnel-assistance.destroy', $personnelAssistance->id) }}"
        method="post" class="form-inline">
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
            makeInputPrice($('#price'));

            activeParentUl('{{ route('admin.admin.personnel-assistance.index') }}');
        })
    </script>
@endsection
