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
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <form class="request-form row forms-sample" method="post" action="{{ route('admin.project.ads.update',$project->id) }}">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            @include('admin.partial.message')
            @csrf
            @method('PATCH')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">کارفرما و پروژه</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">

                    <x-admin.input identify="title" title="نام پروژه" :old="$project->title"/>

                    <x-admin.select-user title="کارفرما" :old="$project->user_id"/>

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
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <x-admin.input identify="domain_primary"
                                   title="نام دامنه اصلی"
                                   :old="$project->domain"/>
                </div>

            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات قرارداد</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body pb-4">

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="agreement_at"
                                           title="تاریخ قرارداد"
                                           :old="verta($project->agreement_at)->format('Y/m/d')"
                                           :is-date-picker="true"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="field_activity"
                                           title="زمینه فعالیت"
                                           :old="$project->target->field_activity"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.select-enum
                                    identify="designed_by"
                                    title="طراحی سایت پروژه"
                                    :with-option="false"
                                    :enum-class="\Modules\Project\app\Enums\ProjectDesignBy::class"
                                    :old="$project->target->designed_by"/>
                        </div>
                    </div>

                    <x-admin.textarea identify="note"
                                      title="اطلاعات بیشتر (یادداشت)"
                                      :old="$project->note"/>

                    <x-admin.button-submit title="ویرایش"/>

                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('project::admin.ads.part.script')

    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.project.ads.index') }}');
        })
    </script>
@endsection
