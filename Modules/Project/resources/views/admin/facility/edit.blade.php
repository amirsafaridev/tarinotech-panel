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
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.facility.index') }}">امکانات جانبی</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.project.facility.update',$facility->id) }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.select-model class="multiple"
                                              identify="base_id"
                                              title="انتخاب نوع پروژه"
                                              key="id"
                                              value="title"
                                              :items="$bases"
                                              :old="$facility->base_id"/>

                        <x-admin.input identify="title" title="عنوان" :old="$facility->title"/>
                            <x-admin.input identify="customer_extra_unit" title="میزان واحد ا کسترا کارفرما" :old="$facility->customer_extra_unit"/>
                            <x-admin.input identify="expert_extra_unit" title="میزان واحد ا کسترا کارشناس" :old="$facility->expert_extra_unit"/>
                                             <x-admin.input identify="duration" title="مدت زمان (روز کاری)" :old="$facility->duration"/>

                                <x-admin.button title="{{ trans('panel.update') }}"/>

                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger" on-click="confirmDelete()"/>
                    </form>

                    <form id="deleteItem" action="{{ route('admin.project.facility.destroy',$facility->id) }}" method="post" class="form-inline">
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
            activeParentUl('{{ route('admin.project.facility.index') }}');
        })
    </script>
@endsection
