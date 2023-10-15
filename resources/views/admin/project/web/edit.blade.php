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
        <h1 class="page-title">پروژه وب سایت - ویرایش</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.web.index') }}">وب سایت ها</a></li>
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

                    <x-admin.input identify="title"
                                   title="نام پروژه"
                                   :old="$project->title"/>

                    <x-admin.select-user title="کارفرما":old="$project->user_id"/>


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
                    <x-admin.checkbox identify="have_domain"
                                      description="دامنه دارد؟"
                                      :old="$project->type->domains['have_domain']"/>

                    <div class="row d-none" id="domain_container">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_provider_website"
                                           title="ادرس سایت ارائه دهنده دامنه"
                                           :old="$project->type->domains['domain_provider_website']"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_username"
                                           title="نام کاربری دامنه"
                                           :old="$project->type->domains['domain_username']"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_password"
                                           title="رمزعبور دامنه"
                                           :old="$project->type->domains['domain_password'] ? Crypt::decrypt($project->type->domains['domain_password']) : ''"/>
                        </div>
                    </div>
                    <x-admin.input identify="domain_primary"
                                   title="نام دامنه اصلی"
                                   :old="$project->domain"/>
                    <x-admin.select-enum
                            identify="domains_required[]"
                            title="دامنه های موردنیاز جهت خرید"
                            :multiple="true"
                            :with-option="false"
                            :enum-class="\App\Enums\Database\Project\WebDomain::class"
                            :old="$project->type->domains['domains_required']"/>

                    <x-admin.input identify="other_domain" title="نام دامنه دیگر را وارد کنید" :old="$project->type->domains['other_domain']"/>
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
                    <x-admin.checkbox identify="have_host"
                                      description="هاست دارد؟"
                                      :old="$project->type->host['have_host']"/>

                    <div class="row d-none" id="host_container">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="host_provider"
                                           title="هاستینگ (از چه سایتی خریداری شده؟)"
                                           :old="$project->type->host['host_provider']"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="host_username"
                                           title="نام کاربری"
                                           :old="$project->type->host['host_username']"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="host_password"
                                           title="رمز"
                                           :old="$project->type->host['host_password'] ? Crypt::decrypt($project->type->host['host_password']) : ''"/>
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-admin.select-enum
                                identify="host_location"
                                title="لوکیشن هاست؟"
                                description="اگر مخاطبان پروژه خارج از کشور هستند و یا برای کارفرما لوکیشن هاست اهمیت دارد"
                                :with-option="false"
                                :enum-class="\App\Enums\Database\Project\WebHostLocation::class"
                                :old="$project->type->host['host_location']"/>
                    </div>

                    <x-admin.checkbox identify="host_most_visit"
                                      description="آیا پروژه نیاز به هاست پربازدید دارد؟"
                                      :old="$project->type->host['host_most_visit']"/>

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
                            :enum-class="\App\Enums\Database\Project\WebLanguage::class"
                            :old="$project->type->language['primary_language']"/>

                    <x-admin.select-enum
                            identify="languages[]"
                            title="زبان ها"
                            :multiple="true"
                            :with-option="false"
                            :enum-class="\App\Enums\Database\Project\WebLanguage::class"
                            :old="$project->type->language['languages']"/>
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
                                    value="title"
                                    :old="$project->type->project_type_id"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.select-model
                                    identify="package_id"
                                    title="پکیج انتخابی"
                                    :items="$packages"
                                    key="id"
                                    value="title"
                                    :old="$project->type->package_id"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="agreement_at"
                                           title="تاریخ قرارداد"
                                           :old="verta($project->agreement_at)->format('Y/m/d')"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="field_activity"
                                           title="زمینه فعالیت"
                                           :old="$project->type->field_activity"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="pages"
                                           title="تعداد صفحات داخلی"
                                           :old="$project->type->pages"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="working_days"
                                           title="مدت زمان (روز کاری)"
                                           :old="$project->type->working_days"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="deadline_at"
                                           title="تاریخ تحویل"
                                           :old="verta($project->deadline_at)->format('Y/m/d')"/>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-success d-flex justify-content-center align-items-center" id="alert_working_days" role="alert">
                                <span class="message">برای محاسبه تاریخ تحویل لطفا عدد روز کاری را وارد کنید.</span>
                            </div>
                        </div>
                    </div>

                    <x-admin.input identify="price"
                                   title="قیمت (ریال)"
                                   :old="number_format($project->price)"/>

                    <x-admin.textarea identify="similar_sites"
                                      title="سایت های مشابه"
                                      description="از نظر موضوعی و زمینه فعالیت مانند رقبا"
                                      :old="implode(PHP_EOL,$project->type->sample['similar_sites'])"/>

                    <x-admin.textarea identify="favorite_sites"
                                      title="سایت های مورد پسند"
                                      :old="implode(PHP_EOL,$project->type->sample['favorite_sites'])"/>

                    <x-admin.select-enum identify="facilities[]"
                                         title="امکانات بیشتر"
                                         :multiple="true"
                                         :with-option="false"
                                         :enum-class="\App\Enums\Database\Project\WebFacility::class"
                                         :old="$project->type->facilities"/>

                    <x-admin.textarea identify="note"
                                      title="اطلاعات بیشتر (یاداشت)"
                                      :old="$project->note"/>

                    <x-admin.button-submit title="{{ trans('panel.update') }}"/>

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
            activeParentUl('{{ route('admin.project.web.index') }}');

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
            deadlineAt.persianDatepicker(dataPickerConfig);

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
        stateDomainContainer({{ $project->type->domains['have_domain'] }});
        function stateDomainContainer(status){
            if(status){
                domainContainer.removeClass('d-none');
            }
            else{
                domainContainer.addClass('d-none');
            }
        }

        const hostContainer = $('#host_container');
        stateHostContainer({{ $project->type->host['have_host'] }});
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
