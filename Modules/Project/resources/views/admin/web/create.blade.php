@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
       \App\Enums\Assets\StyleLoader::Select2(),
        \App\Enums\Assets\StyleLoader::Datepicker(),
        \App\Enums\Assets\StyleLoader::Alert(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.web.index') }}">وب سایت ها</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <form class="request-form row forms-sample" method="post" action="{{ route('admin.project.web.store') }}">
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
                    <x-admin.checkbox identify="have_domain" description="دامنه دارد؟"/>

                    <div class="row d-none" id="domain_container">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_provider_website" title="ادرس سایت ارائه دهنده دامنه"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_username" title="نام کاربری دامنه"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_password" title="رمز عبور دامنه"/>
                        </div>
                    </div>
                    <x-admin.input identify="domain_primary" title="نام دامنه اصلی"/>
                    <x-admin.select-enum
                            identify="domains_required[]"
                            title="دامنه های موردنیاز جهت خرید"
                            :multiple="true"
                            :with-option="false"
                            :enum-class="\Modules\Project\app\Enums\WebDomain::class"/>

                    <x-admin.input identify="other_domain" title="نام دامنه دیگر را وارد کنید"/>
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
                                :enum-class="\Modules\Project\app\Enums\WebHostLocation::class"/>
                    </div>

                    <x-admin.checkbox identify="host_most_visit" description="آیا پروژه نیاز به هاست پربازدید دارد؟"/>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات زبان</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                                    class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <x-admin.select-enum
                            identify="primary_language"
                            title="زبان اصلی"
                            :with-option="false"
                            :enum-class="\Modules\Project\app\Enums\WebLanguage::class"/>

                    <x-admin.select-enum
                            identify="languages[]"
                            title="زبان ها"
                            :multiple="true"
                            :with-option="false"
                            :enum-class="\Modules\Project\app\Enums\WebLanguage::class"/>
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
                            <x-admin.select-model
                                    identify="type_id"
                                    title="نوع پروژه"
                                    :items="$types"
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

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.select-model
                                    identify="package_id"
                                    title="پکیج انتخابی"
                                    :items="$packages"
                                    key="id"
                                    value="title"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="agreement_at"
                                           title="تاریخ قرارداد"
                                           :is-date-picker="true"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="field_activity" title="زمینه فعالیت"/>
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.input identify="pages" title="تعداد صفحات داخلی"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="working_days" title="مدت زمان (روز کاری)"/>
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.input identify="deadline_at"
                                           title="تاریخ تحویل"
                                            :is-date-picker="true"/>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-success d-flex justify-content-center align-items-center"
                                 id="alert_working_days" role="alert">
                                <span class="message">برای محاسبه تاریخ تحویل لطفا عدد روز کاری را وارد کنید.</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-admin.input identify="price" title="قیمت (ریال)"/>
                    </div>

                    <x-admin.textarea identify="similar_sites" title="سایت های مشابه"
                                      description="از نظر موضوعی و زمینه فعالیت مانند رقبا"/>

                    <x-admin.textarea identify="favorite_sites" title="سایت های مورد پسند"/>

                    <x-admin.select-model
                            identify="options[]"
                            title="امکانات بیشتر"
                            key="id"
                            value="title"
                            :multiple="true"
                            :with-option="false"
                            :items="$options"/>

                    <x-admin.textarea identify="note" title="اطلاعات بیشتر (یادداشت)"/>

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
        \App\Enums\Assets\ScriptLoader::Alert(),
    ]])
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('project::admin.web.part.script')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.project.web.index') }}');
        })
    </script>
@endsection
