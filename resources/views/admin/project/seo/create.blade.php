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
        <h1 class="page-title">پروژه سئو - ایجاد</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.seo.index') }}">سئو</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <form class="request-form row forms-sample" method="post" action="{{ $routeStore }}">
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

                    <x-admin.select-model identify="status_id"
                                          title="وضعیت پروژه"
                                          key="id"
                                          value="title"
                                          :items="$statuses"
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
                    <h3 class="card-title">اطلاعات هاست</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                                    class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <x-admin.select-enum
                                identify="host_location"
                                title="هاست"
                                :with-option="false"
                                :enum-class="\App\Enums\Database\Project\SeoHostLocation::class"/>
                    </div>

                    <div class="d-none" id="host_container">
                        <x-admin.input identify="host_provider" title="هاستینگ (از چه سایتی خریداری شده؟)"/>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات سئو</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                                    class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <x-admin.input
                            identify="amount_content"
                            title="میزان تولید محتوا"
                    />

                    <x-admin.input
                            identify="keywords_count"
                            title="تعداد کلمات سئو شدنی"
                    />

                    <x-admin.textarea
                            identify="keywords"
                            description="در هر خط یک کلمه کلیدی با اولویت وارد کنید."
                            title="لیست کلمات قراردادی"
                    />

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
                            <x-admin.input identify="agreement_at"
                                           title="تاریخ قرارداد"
                                           :is-date-picker="true"/>
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.select-enum
                                    identify="agreement_duration"
                                    title="مدت قرارداد"
                                    :with-option="false"
                                    :enum-class="\App\Enums\Database\Project\SeoAgreementDuration::class"/>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="field_activity" title="زمینه فعالیت"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="price" title="قیمت (ریال)"/>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="price_monthly" title="پرداختی ماهیانه (ریال)"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="due_date_payments"
                                           description="چندم هر ماه"
                                           title="تاریخ سررسید پرداخت ها"/>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.select-enum
                                    identify="designed_by"
                                    title="طراحی سایت پروژه"
                                    :with-option="false"
                                    :enum-class="\App\Enums\Database\Project\ProjectDesignBy::class"/>
                        </div>
                    </div>

                    <x-admin.textarea identify="note" title="اطلاعات بیشتر (یادداشت)"/>

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
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.project.seo.index') }}');

            $('#user_id').select2();

            $('#host_location').change(function () {
                if ($(this).val() === 'IN_COMPANY') {
                    stateHostContainer(false);
                } else {
                    stateHostContainer(true);
                }
            });

            jalaliDatepicker.startWatch();
            makeInputPrice($('#price'));
            makeInputPrice($('#price_monthly'));
            makeInputPrice($('#keywords_count'));
        })

        const hostContainer = $('#host_container');

        function stateHostContainer(status) {
            if (status) {
                hostContainer.removeClass('d-none');
            } else {
                hostContainer.addClass('d-none');
            }
        }
    </script>
@endsection
