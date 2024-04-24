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
                            href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.web.index') }}">وب سایت ها</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <form class="request-form row forms-sample" method="post" action="{{ route('admin.project.web.edit',$project->id) }}">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            @include('admin.partial.message')
            @csrf
            @method('PATCH')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">کارفرما و پروژه</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                                    class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">

                    <x-admin.input identify="title"
                                   title="نام پروژه"
                                   :old="$project->title"/>

                    <x-admin.select-user title="کارفرما"
                                         :old="$project->user_id"/>
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
                    <x-admin.checkbox identify="have_domain"
                                      description="دامنه دارد؟"
                                      :old="$project->target->domains['have_domain']"/>

                    <div class="row d-none" id="domain_container">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_provider_website"
                                           title="ادرس سایت ارائه دهنده دامنه"
                                           :old="$project->target->domains['domain_provider_website']"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_username"
                                           title="نام کاربری دامنه"
                                           :old="$project->target->domains['domain_username']"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_password"
                                           title="رمز عبور دامنه"
                                           :old="$project->target->domains['domain_password'] ? Crypt::decrypt($project->target->domains['domain_password']) : ''"/>
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
                            :enum-class="\Modules\Project\app\Enums\WebDomain::class"
                            :old="$project->target->domains['domains_required']"/>

                    <x-admin.input identify="other_domain" title="نام دامنه دیگر را وارد کنید"
                                   :old="$project->target->domains['other_domain']"/>
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
                    <x-admin.checkbox identify="have_host"
                                      description="هاست دارد؟"
                                      :old="$project->target->host['have_host']"/>

                    <div class="row d-none" id="host_container">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="host_provider"
                                           title="هاستینگ (از چه سایتی خریداری شده؟)"
                                           :old="$project->target->host['host_provider']"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="host_username"
                                           title="نام کاربری"
                                           :old="$project->target->host['host_username']"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="host_password"
                                           title="رمز"
                                           :old="$project->target->host['host_password'] ? Crypt::decrypt($project->target->host['host_password']) : ''"/>
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-admin.select-enum
                                identify="host_location"
                                title="لوکیشن هاست؟"
                                description="اگر مخاطبان پروژه خارج از کشور هستند و یا برای کارفرما لوکیشن هاست اهمیت دارد"
                                :with-option="false"
                                :enum-class="\Modules\Project\app\Enums\WebHostLocation::class"
                                :old="$project->target->host['host_location']"/>
                    </div>

                    <x-admin.checkbox identify="host_most_visit"
                                      description="آیا پروژه نیاز به هاست پربازدید دارد؟"
                                      :old="$project->target->host['host_most_visit']"/>

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
                            :enum-class="\Modules\Project\app\Enums\WebLanguage::class"
                            :old="$project->target->language['primary_language']"/>

                    <x-admin.select-enum
                            identify="languages[]"
                            title="زبان ها"
                            :multiple="true"
                            :with-option="false"
                            :enum-class="\Modules\Project\app\Enums\WebLanguage::class"
                            :old="$project->target->language['languages']"/>
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

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.select-model
                                    identify="package_id"
                                    title="پکیج انتخابی"
                                    :items="$packages"
                                    key="id"
                                    value="title"
                                    :old="$project->target->package_id"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="agreement_at"
                                           title="تاریخ قرارداد"
                                           :old="verta($project->agreement_at)->format('Y/m/d')"
                                           :is-date-picker="true"/>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.select-model
                                    identify="business_domain_id"
                                    title="زمینه کاری"
                                    :items="$businessDomains"
                                    :old="$project->business_domain_id"
                                    key="id"
                                    value="title"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="business_domain"
                                           title="زمینه کاری (متنی)"
                                           :old="$project->business_domain"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="pages"
                                           title="تعداد صفحات داخلی"
                                           :old="$project->target->pages"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="working_days"
                                           title="مدت زمان (روز کاری)"
                                           :old="$project->target->working_days"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="deadline_at"
                                           title="تاریخ تحویل"
                                           :old="verta($project->deadline_at)->format('Y/m/d')"
                                           />
                        </div>
                        <div class="col-12">
                            <div class="alert alert-success d-flex justify-content-center align-items-center"
                                 id="alert_working_days" role="alert">
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
                                      :old="implode(PHP_EOL,$project->target->sample['similar_sites'])"/>

                    <x-admin.textarea identify="favorite_sites"
                                      title="سایت های مورد پسند"
                                      :old="implode(PHP_EOL,$project->target->sample['favorite_sites'])"/>

                    <x-admin.select-model
                            identify="options[]"
                            title="امکانات بیشتر"
                            key="id"
                            value="title"
                            :multiple="true"
                            :with-option="false"
                            :items="$options"
                            :old="$project->target->options->pluck('id')->toArray()"
                    />

                    <x-admin.textarea identify="note"
                                      title="اطلاعات بیشتر (یادداشت)"
                                      :old="$project->note"/>

                    <x-admin.button-submit title="{{ trans('panel.update') }}"/>
                    <x-admin.button-delete/>

                </div>
            </div>
        </div>
    </form>

    <form id="deleteItem" action="{{ route('admin.project.web.destroy',$project->id) }}" method="post" class="form-inline">
        @csrf
        @method('DELETE')
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
