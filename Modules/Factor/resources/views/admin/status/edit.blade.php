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
                <li class="breadcrumb-item"><a href="{{ route('admin.factor.status.index') }}">وضعیت های خودکار</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.factor.status.update',$factorStatusForward->id) }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.select-enum identify="factor_status" title="وضعیت فاکتور"
                                             :enum-class="\Modules\Factor\app\Enums\FactorStatus::class"
                                             :old="$factorStatusForward->factor_status"
                        />

                        <x-admin.select-model
                                identify="type_id"
                                title="نوع پروژه"
                                :items="$types"
                                :has-choice-option="false"
                                :old="$factorStatusForward->currentStatus->type->id"
                                key="id"
                                value="title"/>

                        <x-admin.select-simple identify="project_status_id"
                                               title="وضعیت فعلی"

                        />

                        <x-admin.select-simple identify="project_status_forward_id"
                                               title="وضعیت بعدی"

                        />

                        <x-admin.button-submit title="{{ trans('panel.update') }}"/>

                        <x-admin.button-delete/>
                    </form>

                    <form id="deleteItem" action="{{ route('admin.factor.category.destroy',$factorStatusForward->id) }}" method="post" class="form-inline">
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
    @include('factor::admin.status.part.script-status')

    <script>
        $(document).ready(function (){
            activeParentUl('{{ route('admin.factor.category.index') }}');
        })
    </script>
@endsection
