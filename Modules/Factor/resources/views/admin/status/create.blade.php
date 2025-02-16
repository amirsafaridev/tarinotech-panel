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
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.factor.status.index') }}">وضعیت های خودکار</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.factor.status.store') }}">
                        @csrf
                        <x-admin.select-enum identify="factor_status" title="وضعیت فاکتور"
                                             :enum-class="\Modules\Factor\app\Enums\FactorStatus::class"
                                             />

                        <x-admin.select-model
                                identify="type_id"
                                title="نوع پروژه"
                                :items="$types"
                                :has-choice-option="false"
                                key="id"
                                value="title"/>

                        <x-admin.select-simple identify="project_status_id"
                                               title="وضعیت فعلی"

                        />

                        <x-admin.select-simple identify="project_status_forward_id"
                                               title="وضعیت بعدی"

                        />

                        <x-admin.button title="{{ trans('panel.create') }}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('factor::admin.status.part.script-status')
    <script>
        $(document).ready(function (){
            activeParentUl('{{ route('admin.factor.status.index') }}');
        })
    </script>
@endsection
