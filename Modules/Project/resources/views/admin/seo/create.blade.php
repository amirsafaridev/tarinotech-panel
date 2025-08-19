@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', [
        'load' => [
            \App\Enums\Assets\StyleLoader::Toast(),
            \App\Enums\Assets\StyleLoader::Select2(),
            \App\Enums\Assets\StyleLoader::Datepicker(),
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
                <li class="breadcrumb-item"><a href="{{ route('admin.project.seo.index') }}">سئو</a></li>
                <li class="breadcrumb-item active">ایجاد</li>
            </ol>
        </div>
    </div>

    <form class="request-form row forms-sample" method="post" action="{{ route('admin.project.seo.store') }}">
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

                    <x-admin.select-model identify="type_id" title="نوع پروژه" :items="$types" :has-choice-option="false"
                        key="id" value="title" />

                    <x-admin.select-simple identify="status_id" title="وضعیت پروژه" />
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
                    <x-admin.input identify="domain_primary" title="نام دامنه اصلی" />
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
                        <x-admin.select-enum identify="host_location" title="هاست" :with-option="false" :enum-class="\Modules\Project\app\Enums\SeoHostLocation::class" />
                    </div>

                    <div class="d-none" id="host_container">
                        <x-admin.input identify="host_provider" title="هاستینگ (از چه سایتی خریداری شده؟)" />
                    </div>
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
                            <x-admin.select-model identify="package_id" title="پکیج انتخابی" :items="$packages"
                                key="id" value="title" />
                        </div>
                        <div class="col-12 col-md-6">

                            <x-admin.select-model identify="facilities[]" title="ویژگی‌ها" key="id" value="title"
                                :multiple="true" :with-option="false" :items="$facilities" />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.input identify="total_workdays" readOnly='true' title="مدت زمان (روز کاری)" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="agreement_at" title="تاریخ قرارداد" :is-date-picker="true" />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.select-enum identify="agreement_duration" title="مدت قرارداد" :with-option="true"
                                :enum-class="\Modules\Project\app\Enums\SeoAgreementDuration::class" />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.input identify="field_activity" title="زمینه فعالیت" />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.input identify="price" title="قیمت (ریال)" />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.input identify="price_monthly" title="پرداختی ماهیانه (ریال)" :read-only="true" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="due_date_payments" description="چندم هر ماه"
                                title="تاریخ سررسید پرداخت ها" />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.select-enum identify="designed_by" title="طراحی سایت پروژه" :with-option="false"
                                :enum-class="\Modules\Project\app\Enums\ProjectDesignBy::class" />
                        </div>
                    </div>

                    <x-admin.textarea identify="note" title="اطلاعات بیشتر (یادداشت)" />

                    <x-admin.textarea identify="contract_attachment" title="پیوست قرارداد" />

                    <x-admin.button title="{{ trans('panel.create') }}" />

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
                    <x-admin.input identify="amount_content" title="میزان تولید محتوا" :read-only="true" />

                    <x-admin.input identify="keywords_count" title="تعداد کلمات سئو شدنی" :read-only="true" />

                    <x-admin.select-simple identify="keywords[]" :multiple="true"
                        description="در هر خط یک کلمه کلیدی با اولویت وارد کنید." title="لیست کلمات قراردادی" />

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
    @include('project::admin.seo.part.script')
    <script>
        $(document).ready(function() {
            activeParentUl('{{ route('admin.project.seo.index') }}');
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
