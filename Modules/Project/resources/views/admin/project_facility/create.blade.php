@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::Datepicker(),
   ]])
@endsection

@section('content')

    <div class="page-header">
        <h1 class="page-title">امکانات جانبی - ایجاد</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ $routeStore }}">
                        @csrf

                        <x-admin.select-model identify="facility_id" title="انتخاب امکان جانبی" :items="$facilities" key="id" value="title"/>

                        <x-admin.select-enum identify="price_type" title="قیمت ایجاد" :enum-class="\App\Enums\Database\Facility\PriceType::class" />
                        <div id="price_value_wrapper" class="d-none">
                            <x-admin.input identify="price_value" title="قیمت مورد نظر (ریال)" />
                        </div>

                        <x-admin.select-enum identify="work_cycle" title="سیکل کاری تمدید" :enum-class="\App\Enums\Database\Facility\WorkCycle::class" />
                        <div id="work_cycle_value_wrapper" class="d-none">
                            <x-admin.input identify="work_cycle_value" title="تاریخ مورد نظر" :is-date-picker="true"/>
                        </div>

                        <x-admin.select-enum identify="financial_cycle" title="سیکل مالی" :enum-class="\App\Enums\Database\Facility\FinancialCycle::class" />
                        <div id="financial_cycle_value_wrapper" class="d-none">
                            <x-admin.input identify="financial_cycle_value" title="قیمت مورد نظر (ریال)" />
                        </div>

                        <x-admin.input identify="added_at" title="تاریخ ایجاد" :is-date-picker="true" />

                        <x-admin.textarea identify="description" title="توضیحات" />

                        <x-admin.button title="{{ trans('panel.create') }}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
       \App\Enums\Assets\ScriptLoader::Datepicker(),
   ]])
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function (){
            activeParentUl('{{ route('admin.project.index') }}');
            jalaliDatepicker.startWatch();

            const priceType = $('#price_type');
            const priceValue = $('#price_value');
            const priceValueWrapper = $('#price_value_wrapper');
            priceType.change(function (){
                const _this = $(this);
                if(parseInt(_this.val()) === parseInt('{{ \App\Enums\Database\Facility\PriceType::Input }}')){
                    priceValueWrapper.removeClass('d-none');
                }
                else{
                    priceValueWrapper.addClass('d-none');
                }
            })
            makeInputPrice(priceValue);

            const workCycle = $('#work_cycle');
            const workCycleValue = $('#work_cycle_value');
            const workCycleValueWrapper = $('#work_cycle_value_wrapper');
            workCycle.change(function (){
                const _this = $(this);
                if(parseInt(_this.val()) === parseInt('{{ \App\Enums\Database\Facility\WorkCycle::InputDate }}')){
                    workCycleValueWrapper.removeClass('d-none');
                }
                else{
                    workCycleValueWrapper.addClass('d-none');
                    workCycleValue.val('');
                }
            })

            const financialCycle = $('#financial_cycle');
            const financialCycleValue = $('#financial_cycle_value');
            const financialCycleValueWrapper = $('#financial_cycle_value_wrapper');
            financialCycle.change(function (){
                const _this = $(this);
                if(parseInt(_this.val()) === parseInt('{{ \App\Enums\Database\Facility\FinancialCycle::InputPrice }}')){
                    financialCycleValueWrapper.removeClass('d-none');
                }
                else{
                    financialCycleValueWrapper.addClass('d-none');
                    financialCycleValue.val('');
                }
            })
            makeInputPrice(financialCycleValue);
        });
    </script>
@endsection
