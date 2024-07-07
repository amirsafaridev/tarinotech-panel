@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Toast(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.web.index') }}">وب سایت ها</a></li>
                <li class="breadcrumb-item active">ویرایش - وضعیت</li>
            </ol>
        </div>
    </div>

    <form class="request-form row forms-sample" method="post"
          action="{{ route('admin.project.web.update.status',$project->id) }}">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            @include('admin.partial.message')
            @csrf
            @method('PATCH')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ویرایش وضعیت</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                                    class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body pb-4">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.select-model
                                    identify="type_id"
                                    title="نوع پروژه"
                                    :items="$types"
                                    :old="$project->type_id"
                                    :has-choice-option="false"
                                    key="id"
                                    value="title"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.select-simple identify="status_id"
                                                   title="وضعیت پروژه"

                            />
                        </div>


                    </div>

                    <x-admin.button title="{{ trans('panel.update') }}"/>

                </div>
            </div>
        </div>
    </form>

    @include('log::admin.part.table',['logTitle'=>'تست','itemsProperties'=>$statuses])

@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
    ]])
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('project::admin.web.part.script-status')

    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.project.web.index') }}');
        })
    </script>
@endsection
