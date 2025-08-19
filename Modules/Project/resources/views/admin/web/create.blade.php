@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', [
        'load' => [
            \App\Enums\Assets\StyleLoader::Select2(),
            \App\Enums\Assets\StyleLoader::Toast(),
            \App\Enums\Assets\StyleLoader::Datepicker(),
            \App\Enums\Assets\StyleLoader::Alert(),
        ],
    ])
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

                    <x-admin.input identify="title" title="نام پروژه" />

                    <x-admin.select-user title="کارفرما" />

                    @role(\App\Enums\Database\Role\RoleName::SUPER_ADMIN)
                        <x-admin.select-model title="کارشناس فروش" identify="admin_id" :items="$admins" key="id"
                            value="fullName" />
                    @endrole

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
                    <x-admin.checkbox identify="have_domain" description="دامنه دارد؟" />

                    <div class="row d-none" id="domain_container">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_provider_website" title="ادرس سایت ارائه دهنده دامنه" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_username" title="نام کاربری دامنه" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="domain_password" title="رمز عبور دامنه" />
                        </div>
                    </div>
                    <x-admin.input identify="domain_primary" title="نام دامنه اصلی" />
                    <x-admin.select-enum identify="domains_required[]" title="دامنه های موردنیاز جهت خرید" :multiple="true"
                        :with-option="false" :enum-class="\Modules\Project\app\Enums\WebDomain::class" />

                    <x-admin.input identify="other_domain" title="نام دامنه دیگر را وارد کنید" />
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
                    <x-admin.checkbox identify="have_host" description="هاست دارد؟" />

                    <div class="row d-none" id="host_container">
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="host_provider" title="هاستینگ (از چه سایتی خریداری شده؟)" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="host_username" title="نام کاربری" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="host_password" title="رمز" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-admin.select-enum identify="host_location" title="لوکیشن هاست؟"
                            description="اگر مخاطبان پروژه خارج از کشور هستند و یا برای کارفرما لوکیشن هاست اهمیت دارد"
                            :with-option="false" :enum-class="\Modules\Project\app\Enums\WebHostLocation::class" />
                    </div>

                    <x-admin.checkbox identify="host_most_visit" description="آیا پروژه نیاز به هاست پربازدید دارد؟" />

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
                    <x-admin.select-enum identify="primary_language" title="زبان اصلی" :with-option="false"
                        :enum-class="\Modules\Project\app\Enums\WebLanguage::class" />

                    <x-admin.select-enum identify="languages[]" title="زبان ها" :multiple="true" :with-option="false"
                        :enum-class="\Modules\Project\app\Enums\WebLanguage::class" />
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
                            <x-admin.select-model identify="type_id" title="نوع پروژه" :items="$types"
                                :has-choice-option="false" key="id" value="title" />

                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.select-simple identify="status_id" title="وضعیت پروژه" />
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.select-model identify="package_id" title="پکیج انتخابی" :items="$packages"
                                key="id" value="title" />

                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="agreement_at" title="تاریخ قرارداد" :is-date-picker="true" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <x-admin.select-model identify="business_domain_id" title="زمینه کاری" :items="$businessDomains"
                                key="id" value="title" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="business_domain" title="زمینه کاری (متنی)" />
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-12 col-md-6">
                            <x-admin.input identify="pages" title="تعداد صفحات داخلی" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="total_workdays" readOnly='true' title="مدت زمان (روز کاری)" />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.input identify="deadline_at" title="تاریخ تحویل" :is-date-picker="true" />
                        </div>

                        <div class="col-12">
                            <div class="alert alert-success d-flex justify-content-center align-items-center"
                                id="alert_working_days" role="alert">
                                <span class="message">عدد روز کاری از طریق پکیج انتخابی و ویژگی ها محاسبه میشود</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-admin.input identify="price" title="قیمت (ریال)" />
                    </div>

                    <x-admin.textarea identify="similar_sites" title="سایت های مشابه"
                        description="از نظر موضوعی و زمینه فعالیت مانند رقبا" />

                    <x-admin.textarea identify="favorite_sites" title="سایت های مورد پسند" />

                    <x-admin.select-model identify="facilities[]" title="ویژگی‌ها" key="id" value="title"
                        :multiple="true" :with-option="false" :items="$facilities" />



                    <x-admin.textarea identify="note" title="اطلاعات بیشتر (یادداشت)" />

                    <x-admin.textarea identify="contract_attachment" title="پیوست قرارداد" />

                    <x-admin.button title="{{ trans('panel.create') }}" />

                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script', [
        'load' => [
            \App\Enums\Assets\ScriptLoader::Select2(),
            \App\Enums\Assets\ScriptLoader::Datepicker(),
            \App\Enums\Assets\ScriptLoader::Alert(),
            \App\Enums\Assets\ScriptLoader::CKEditor(),
        ],
    ])
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('admin.partial.ckeditor')
    @include('project::admin.web.part.script')
    <script>
        $(document).ready(function() {
            activeParentUl('{{ route('admin.project.web.index') }}');
        })
        const facilitiesData = @json($facilities);
        const packagesData = @json($packages);

        function calculateTotalWorkdays() {
            let total = 0;

            // facilities (multiple select)
            $('#facilities option:selected').each(function() {
                const selectedId = parseInt($(this).val());
                const facility = facilitiesData.find(f => f.id === selectedId);
                if (facility && facility.duration) {
                    total += parseFloat(facility.duration);
                }
            });

            // package (single select)
            const selectedPackageId = parseInt($('#package_id').val());
            const selectedPackage = packagesData.find(p => p.id === selectedPackageId);
            if (selectedPackage && selectedPackage.duration) {
                total += parseFloat(selectedPackage.duration);
            }

            $('#total_workdays').val(total);
        }

        $(document).on('change click', '#facilities, #package_id', calculateTotalWorkdays);
        $(document).ready(calculateTotalWorkdays);
    </script>
@endsection
