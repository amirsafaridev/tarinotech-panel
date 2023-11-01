@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Toast(),
        \App\Enums\Assets\StyleLoader::Datepicker(),
        \App\Enums\Assets\StyleLoader::Select2(),
        \App\Enums\Assets\StyleLoader::Alert(),
    ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">پرسنل - ویرایش</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.index') }}">{{ trans('panel.admin.title') }}</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <form class="row request-form forms-sample" enctype="multipart/form-data" method="post" action="{{ $routeUpdate }}">

        <div class="col-12">
            @include('admin.partial.message')
            @csrf
            @method('PUT')
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات ورود</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse"
                           data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">

                    <x-admin.input identify="email" :read-only="true" :disabled="true" title="پست الکترونیکی" :old="$admin->email"/>

                    <x-admin.input identify="mobile" title="شماره همراه" :old="$admin->mobile"/>

                    <x-admin.select-model multiple="multiple"
                                          identify="role[]"
                                          title="سطح دسترسی"
                                          :items="$roles"
                                          key="id"
                                          value="name"/>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات پروفایل</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse"
                           data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">

                    @if($admin->avatar)
                        <img class="admin-avatar" src="{{ asset($admin->avatar) }}" alt="{{ $admin->first_name }} {{ $admin->last_name }}">
                    @endif

                    <x-admin.input identify="avatar" title="تصویر" type="file"/>

                    <x-admin.input identify="first_name" title="نام" :old="$admin->first_name"/>

                    <x-admin.input identify="last_name" title="نام خانوادگی" :old="$admin->last_name"/>

                    <x-admin.input identify="dob" title="تاریخ تولد" :old="$admin->dob ? $admin->dob->toJalali()->format('Y/m/d') : ''"/>

                    <x-admin.textarea identify="resume" title="رزومه" :old="$admin->resume"/>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">اطلاعات شرکتی</h3>
                    <div class="card-options">
                        <a href="javascript:void(0)" class="card-options-collapse"
                           data-bs-toggle="card-collapse"><i class="fal fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">

                    <x-admin.input identify="mobile_company" title="شماره همراه شرکتی" :old="$admin->mobile_company"/>

                    <x-admin.input identify="number_company" title="شماره داخلی" :old="$admin->number_company"/>

                    <x-admin.input identify="start_cooperation" title="شروع همکاری"
                                   :old="$admin->start_cooperation ? $admin->start_cooperation->toJalali()->format('Y/m/d') : ''"/>

                    <x-admin.input identify="start_last_contract" title="تاریخ شروع آخرین قرارداد"
                                   :old="$admin->start_last_contract ? $admin->start_last_contract->toJalali()->format('Y/m/d') : ''"/>

                    <x-admin.input identify="end_last_contract" title="تاریج پایان آخرین قرارداد"
                                   :old="$admin->end_last_contract ? $admin->end_last_contract->toJalali()->format('Y/m/d') : ''"/>

                    <x-admin.textarea identify="description" title="توضیحات" :old="$admin->description"/>

                    <x-admin.checkbox identify="has_access" description="بلاک شود" :old="$admin->has_access"/>

                    <x-admin.button-submit title="{{ trans('panel.update') }}"/>

                    <x-admin.button-delete/>
                </div>
            </div>
        </div>

    </form>

    <form id="deleteItem" action="{{ $routeDestroy }}" method="post" class="form-inline">
        @csrf
        @method('DELETE')
    </form>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::CKEditor(),
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    @include('admin.partial.ckeditor')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.admin.index') }}');

            CKEDITOR.replace( 'description');

            const dataPickerConfig = {
                    format: 'YYYY/MM/DD',
                    initialValueType: 'persian',
                    initialValue: false,
                    autoClose: true
            };

            $('#dob').persianDatepicker(dataPickerConfig);
            $('#start_cooperation').persianDatepicker(dataPickerConfig);
            $('#start_last_contract').persianDatepicker(dataPickerConfig);
            $('#end_last_contract').persianDatepicker(dataPickerConfig);

            @if($oldRoles->isNotEmpty())
                const roles ="{{ $oldRoles->pluck('id')->implode(',') }}";
                $.each(roles.split(","), function(i,e){
                    $("#role option[value='" + e + "']").prop("selected", true);
                })
            @endif

            $('#role').select2();
        })
    </script>
@endsection
