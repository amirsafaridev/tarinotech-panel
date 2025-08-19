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
                <li class="breadcrumb-item"><a href="{{ route('admin.project.seo.index') }}">سئو</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <form class="request-form row forms-sample" method="post"
        action="{{ route('admin.project.seo.update', $project->id) }}">
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

                    <x-admin.input identify="title" title="نام پروژه" :old="$project->title" />

                    <x-admin.select-user title="کارفرما" :old="$project->user_id" />

                    @role(\App\Enums\Database\Role\RoleName::SUPER_ADMIN)
                        <x-admin.select-model title="کارشناس فروش" identify="admin_id" :old="$project->admin_id" :items="$admins"
                            key="id" value="fullName" />
                    @endrole

                    <x-admin.select-model identify="type_id" title="نوع پروژه" :items="$types" :has-choice-option="false"
                        key="id" value="title" />

                    <x-admin.select-model identify="status_id" title="وضعیت پروژه" key="id" value="title"
                        :items="$statuses" :old="$project->project_status_id" />
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
                    <x-admin.input identify="domain_primary" title="نام دامنه اصلی" :old="$project->domain" />
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
                        <x-admin.select-enum identify="host_location" title="هاست" :with-option="false" :enum-class="\Modules\Project\app\Enums\SeoHostLocation::class"
                            :old="$project->target->host['host_location']" />
                    </div>

                    <div class="d-none" id="host_container">
                        <x-admin.input identify="host_provider" title="هاستینگ (از چه سایتی خریداری شده؟)"
                            :old="$project->target->host['host_provider']" />
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
                                key="id" value="title" :old="$project->target->package_id" />
                        </div>
                        <div class="col-12 col-md-6">

                            <x-admin.select-model identify="facilities[]" title="ویژگی‌ها" key="id" value="title"
                                :multiple="true" :with-option="false" :items="$facilities" :old="$project->facilities->pluck('id')->toArray()" />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.input identify="total_workdays" readOnly='true' title="مدت زمان (روز کاری)" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="agreement_at" title="تاریخ قرارداد" :old="verta($project->agreement_at)->format('Y/m/d')"
                                :is-date-picker="true" />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.select-enum identify="agreement_duration" title="مدت قرارداد" :with-option="false"
                                :enum-class="\Modules\Project\app\Enums\SeoAgreementDuration::class" :old="$project->target->agreement_duration" />
                        </div>


                        <div class="col-12 col-md-6">
                            <x-admin.input identify="field_activity" title="زمینه فعالیت" :old="$project->target->field_activity" />
                        </div>
                        @if (hasAdminPermission(\App\Enums\Database\Role\PermissionName::PROJECT_PRICE_EDIT))
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="price" title="قیمت (ریال)" :old="number_format($project->price)" />
                            </div>
                        @endif


                        <div class="col-12 col-md-6">
                            <x-admin.input identify="price_monthly" title="پرداختی ماهیانه (ریال)" :old="number_format($project->target->price_monthly)" />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin.input identify="due_date_payments" description="چندم هر ماه"
                                title="تاریخ سررسید پرداخت ها" :old="$project->target->due_date_payments" />
                        </div>

                        <div class="col-12 col-md-6">
                            <x-admin.select-enum identify="designed_by" title="طراحی سایت پروژه" :with-option="false"
                                :enum-class="\Modules\Project\app\Enums\ProjectDesignBy::class" :old="$project->target->designed_by" />
                        </div>
                    </div>

                    <x-admin.textarea identify="note" title="اطلاعات بیشتر (یادداشت)" :old="$project->note" />

                    <x-admin.textarea identify="contract_attachment" title="پیوست قرارداد" :old="$project->contract_attachment" />

                    <x-admin.button title="ویرایش" />

                    @can('ADMIN_PROJECT_SEO_DESTROY')
                        <x-admin.button title="{{ trans('panel.delete') }}" type="button" color="danger"
                            on-click="confirmDelete()" />
                    @endcan

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
                    <x-admin.input identify="amount_content" title="میزان تولید محتوا" :old="$project->target->amount_content" />

                    <x-admin.input identify="keywords_count" title="تعداد کلمات سئو شدنی" :old="$project->target->keywords_count" />


                    <div class="form-group">
                        <label for="keywords" class="form-label">لیست کلمات قراردادی</label>
                        <select class="form-control" name="keywords[]" multiple="multiple" id="keywords">
                            <option value="">انتخاب گزینه</option>
                            @foreach ($project->target->keywords as $keyword)
                                <option selected="selected" value="{{ $keyword }}">
                                    {{ $keyword }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>
    </form>
    <form id="deleteItem" action="{{ route('admin.project.seo.destroy', $project->id) }}" method="post"
        class="form-inline">
        @csrf
        @method('DELETE')
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script', [
        'load' => [
            \App\Enums\Assets\ScriptLoader::Datepicker(),
            \App\Enums\Assets\ScriptLoader::Select2(),
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

        })
    </script>
@endsection
