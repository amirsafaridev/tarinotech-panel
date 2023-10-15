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
        <h1 class="page-title">پروژه سئو - ویرایش</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.seo.index') }}">سئو</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <form class="request-form row forms-sample" method="post" action="{{ $routeUpdate }}">
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

                    <x-admin.select-model identify="status_id"
                                          title="وضعیت پروژه"
                                          key="id"
                                          value="title"
                                          :items="$statuses"
                                          :old="$project->project_status_id"
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
                    <h3 class="card-title">اطلاعات هاست</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body" >

                    <div class="mb-3">
                        <x-admin.select-enum
                                identify="host_location"
                                title="هاست"
                                :with-option="false"
                                :enum-class="\App\Enums\Database\Project\SeoHostLocation::class"
                                :old="$project->type->host['host_location']"/>
                    </div>

                    <div class="d-none" id="host_container">
                        <x-admin.input identify="host_provider"
                                       title="هاستینگ (از چه سایتی خریداری شده؟)"
                                       :old="$project->type->host['host_provider']"/>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات سئو</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body" >
                    <x-admin.input
                            identify="amount_content"
                            title="میزان تولید محتوا"
                            :old="$project->type->amount_content"
                    />

                    <x-admin.input
                            identify="keywords_count"
                            title="تعداد کلمات سئو شدنی"
                            :old="$project->type->keywords_count"
                    />

                    <x-admin.textarea
                            identify="keywords"
                            description="در هر خط یک کلمه کلیدی با اولویت وارد کنید."
                            title="لیست کلمات قراردادی"
                            :old="$project->type->keywords"
                    />

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
                                           :old="verta($project->agreement_at)->format('Y/m/d')"/>
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.select-enum
                                    identify="agreement_duration"
                                    title="مدت قرارداد"
                                    :with-option="false"
                                    :enum-class="\App\Enums\Database\Project\SeoAgreementDuration::class"
                                    :old="$project->type->agreement_duration"/>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="field_activity"
                                           title="زمینه فعالیت"
                                           :old="$project->type->field_activity"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="price"
                                           title="قیمت (ریال)"
                                           :old="number_format($project->price)"/>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="price_monthly"
                                           title="پرداختی ماهیانه (ریال)"
                                           :old="number_format($project->type->price_monthly)"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="due_date_payments"
                                           description="چندم هر ماه"
                                           title="تاریخ سررسید پرداخت ها"
                                           :old="$project->type->due_date_payments"/>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.select-enum
                                    identify="designed_by"
                                    title="طراحی سایت پروژه"
                                    :with-option="false"
                                    :enum-class="\App\Enums\Database\Project\ProjectDesignBy::class"
                                    :old="$project->type->designed_by"/>
                        </div>
                    </div>

                    <x-admin.textarea identify="note"
                                      title="اطلاعات بیشتر (یاداشت)"
                                      :old="$project->note"/>

                    <x-admin.button-submit/>

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
    @include('admin.partial.share-script')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.project.seo.index') }}');

            $('#user_id').select2();

            $('#host_location').change(function (){
                if($(this).val() === 'IN_COMPANY'){
                    stateHostContainer(false);
                }
                else{
                    stateHostContainer(true);
                }
            }).trigger('change');


            const dataPickerConfig = {
                format: 'YYYY/MM/DD',
                initialValueType: 'persian',
                initialValue: false,
                autoClose: true
            };

            $('#agreement_at').persianDatepicker(dataPickerConfig);
            makeInputPrice($('#price'));
            makeInputPrice($('#price_monthly'));
            makeInputPrice($('#keywords_count'));
        })

        const hostContainer = $('#host_container');
        function stateHostContainer(status){
            if(status){
                hostContainer.removeClass('d-none');
            }
            else{
                hostContainer.addClass('d-none');
            }
        }
    </script>
@endsection
