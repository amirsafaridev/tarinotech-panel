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
        <h1 class="page-title">پروژه وب سایت - ایجاد</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.web.index') }}">وب سایت ها</a></li>
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
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">

                    <x-admin.input identify="title" title="نام پروژه"/>

                    <x-admin.select-user/>

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
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <x-admin.checkbox identify="have_domain" description="دامنه دارد؟"/>

                    <div class="row d-none" id="domain_container">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_provider_website" title="ادرس سایت ارائه دهنده دامنه"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_username" title="نام کاربری دامنه"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_password" title="رمزعبور دامنه"/>
                        </div>
                    </div>
                    <x-admin.input identify="domain_primary" title="نام دامنه اصلی"/>
                    <x-admin.select-enum
                            identify="domains_required[]"
                            title="دامنه های موردنیاز جهت خرید"
                            :multiple="true"
                            :with-option="false"
                            :enum-class="\App\Enums\Database\Project\WebDomain::class"/>

                    <x-admin.input identify="other_domain" title="نام دامنه دیگر را وارد کنید"/>
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
                    <x-admin.checkbox identify="have_host" description="هاست دارد؟"/>

                    <div class="row d-none" id="host_container">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="host_provider" title="هاستینگ (از چه سایتی خریداری شده؟)"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="host_username" title="نام کاربری"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="host_password" title="رمز"/>
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-admin.select-enum
                                identify="host_location"
                                title="لوکیشن هاست؟"
                                description="اگر مخاطبان پروژه خارج از کشور هستند و یا برای کارفرما لوکیشن هاست اهمیت دارد"
                                :with-option="false"
                                :enum-class="\App\Enums\Database\Project\WebHostLocation::class"/>
                    </div>

                    <x-admin.checkbox identify="host_most_visit" description="آیا پروژه نیاز به هاست پربازدید دارد؟"/>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات ربان</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body" >
                    <x-admin.select-enum
                            identify="primary_language"
                            title="زبان اصلی"
                            :with-option="false"
                            :enum-class="\App\Enums\Database\Project\WebLanguage::class"/>

                    <x-admin.select-enum
                            identify="languages[]"
                            title="زبان ها"
                            :multiple="true"
                            :with-option="false"
                            :enum-class="\App\Enums\Database\Project\WebLanguage::class"/>
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
                            <x-admin.select-model
                                    identify="project_type_id"
                                    title="نوع پروژه"
                                    :items="$projectTypes"
                                    key="id"
                                    value="title"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.select-model
                                    identify="package_id"
                                    title="پکیج انتخابی"
                                    :items="$packages"
                                    key="id"
                                    value="title"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="agreement_at" title="تاریخ قرارداد"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="field_activity" title="زمینه فعالیت"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="pages" title="تعداد صفحات داخلی"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="working_days" title="مدت زمان (روز کاری)"/>
                            <x-admin.input identify="deadline_at" type="hidden"/>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-success d-flex justify-content-center align-items-center" id="alert_working_days" role="alert">
                                <span class="message">برای محاسبه تاریخ تحویل لطفا عدد روز کاری را وارد کنید.</span>
                            </div>
                        </div>
                    </div>

                    <x-admin.input identify="price" title="قیمت (ریال)"/>

                    <x-admin.textarea identify="similar_sites" title="سایت های مشابه" description="از نظر موضوعی و زمینه فعالیت مانند رقبا"/>

                    <x-admin.textarea identify="favorite_sites" title="سایت های مورد پسند"/>

                    <x-admin.select-enum
                            identify="facilities[]"
                            title="امکانات بیشتر"
                            :multiple="true"
                            :with-option="false"
                            :enum-class="\App\Enums\Database\Project\WebFacility::class"/>

                    <x-admin.textarea identify="note" title="اطلاعات بیشتر (یاداشت)"/>

                    <x-admin.button-submit/>

                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Select2(),
         \App\Enums\Assets\ScriptLoader::Datepicker(),
    ]])
    @include('admin.partial.request')
    @include('admin.partial.share-script')
    <script>
        $(document).ready(function () {
            $('#user_id').select2();
            $('#domains_required').select2();
            $('#languages').select2();
            $('#facilities').select2();

            $('#have_domain').change(function (){
                stateDomainContainer($(this).is(':checked'));
            });

            $('#have_host').change(function (){
                stateHostContainer($(this).is(':checked'));
            });

            const dataPickerConfig = {
                format: 'YYYY/MM/DD',
                initialValueType: 'persian',
                initialValue: false,
                autoClose: true
            };

            $('#agreement_at').persianDatepicker(dataPickerConfig);

            const deadlineAt = $('#deadline_at');
            const workingDays = $('#working_days');
            const alertWorkingDays = $('#alert_working_days');
            const alertDaysCalcMessage = $('#alert_working_days .message');
            let debounceTimer;

            workingDays.on('keyup', function() {
                const self = $(this);
                if(!self.val()){
                    return;
                }
                clearTimeout(debounceTimer);
                alertDaysCalcMessage.html('<span class="fal fa-spinner fa-spin"></span>');
                debounceTimer = setTimeout(function() {
                    postAjax('{{ route('admin.ajax.calc-day-work') }}', {
                        days:self.val()
                    })
                    .then(function (response) {
                        let totalWorkDays = response.total_work_days;
                        let totalFreeDays = response.total_free_days;
                        let finalDateJalali = response.final_date_jalali;
                        let finalDate = response.final_date;
                        let updatedMessage = `تعداد روز های محاسبه شده ${totalWorkDays} می باشد و تعداد روز های تعطیل محاسبه شده ${totalFreeDays} می باشد. تاریخ تحویل ${finalDateJalali} می باشد`;
                        alertDaysCalcMessage.html(updatedMessage);
                        deadlineAt.val(finalDate);
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
                }, 2000);
            });
            makeInputNumber(workingDays);
            makeInputPrice($('#price'));
        })


        const domainContainer = $('#domain_container');
        function stateDomainContainer(status){
            if(status){
                domainContainer.removeClass('d-none');
            }
            else{
                domainContainer.addClass('d-none');
            }
        }

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
