@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
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
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.ads.index') }}">گوگل ادز</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <form class="request-form row forms-sample" method="post" action="{{ route('admin.project.ads.store') }}">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            @include('admin.partial.message')
            @csrf

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">کارفرما و پروژه</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                                    class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">

                    <x-admin.input identify="title" title="نام پروژه"/>

                    <x-admin.select-user title="کارفرما"/>

                    <x-admin.select-model
                            identify="type_id"
                            title="نوع پروژه"
                            :items="$types"
                            :has-choice-option="false"
                            key="id"
                            value="title"/>

                    <x-admin.select-simple identify="status_id"
                                           title="وضعیت پروژه"

                    />
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات دامنه</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                                    class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <x-admin.input identify="domain_primary" title="نام دامنه اصلی"/>
                </div>

            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات قرارداد</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                                    class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body pb-4">

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.input
                                    identify="agreement_at"
                                    title="تاریخ قرارداد"
                                    :is-date-picker="true"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="field_activity" title="زمینه فعالیت"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.select-enum
                                    identify="designed_by"
                                    title="طراحی سایت پروژه"
                                    :with-option="false"
                                    :enum-class="\Modules\Project\app\Enums\ProjectDesignBy::class"/>
                        </div>
                    </div>

                    <x-admin.textarea identify="note" title="اطلاعات بیشتر (یادداشت)"/>

                    <x-admin.textarea identify="contract_attachment" title="پیوست قرارداد"/>

                    <x-admin.button title="{{ trans('panel.create') }}"/>

                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Select2(),
        \App\Enums\Assets\ScriptLoader::CKEditor(),

    ]])
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('admin.partial.ckeditor')
    @include('project::admin.ads.part.script')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.project.ads.index') }}');
        })
    </script>
@endsection
