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
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">مشتری ها</a></li>
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.user.update',$user->id) }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.input identify="mobile" :title="trans('fields.admin.mobile')" :old="$user->mobile" :disabled="true" />

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="first_name" title="نام" :old="$user->first_name" />
                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="en_first_name" title="به لاتین" :old="$user->en_first_name" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="last_name" title="نام خانوادگی" :old="$user->last_name" />

                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="en_last_name" title="به لاتین" :old="$user->en_last_name" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="father_name" title="نام پدر" :old="$user->father_name" />

                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="national_id" title="کد ملی" :old="$user->national_id" />
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="document_id" title="شماره شناسنامه" :old="$user->document_id" />
                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="dob" title="تاریخ تولد" :old="$user->dob" :is-date-picker="true"/>
                            </div>
                        </div>

                        <x-admin.select-enum identify="person_type" title="نوع شخص" :enum-class="\Modules\User\app\Enums\PersonType::class" :old="$user->person_type"/>

                        <div id="company_container" class="d-none">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <x-admin.input identify="company_name" title="نام شرکت" :old="$user->company?->name" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <x-admin.select-enum identify="company_type" title="نوع شرکت" :enum-class="\App\Enums\Database\Company\CompanyType::class" :old="$user->company?->type"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <x-admin.input identify="company_identify" title="شناسه ملی شرکت" :old="$user->company_identify" :old="$user->company?->identify"/>
                                </div>
                                <div class="col-12 col-md-6">
                                    <x-admin.input identify="company_register_id" title="شماره ثبت شرکت" :old="$user->company_register_id" :old="$user->company?->register_id"/>
                                </div>
                            </div>
                        </div>

                        <x-admin.textarea identify="address" rows="5" title="آدرس" :old="$user->address->address" />

                        <x-admin.input identify="postal_code" title="کدپستی" :old="$user->address->postal_code" />


                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="email" title="پست الکترونیکی" type="email" :old="$user->email"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="tel" title="تلفن ثابت" :old="$user->tel" />
                            </div>
                        </div>

                        <x-admin.select-enum identify="irnic_status" title="شناسه ایرنیک" :enum-class="\Modules\User\app\Enums\IrnicStatus::class" :old="$user->irnic->status"/>

                        <div id="irnic_container" class="d-none">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <x-admin.input identify="irnic_identify" title=" شناسه ایرنیک" :old="$user->irnic->identify" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <x-admin.input identify="irnic_password" title="رمزعبور ایرنیک" :old="$user->irnic->password" />
                                </div>
                            </div>
                        </div>

                        <x-admin.input identify="avatar" :title="trans('fields.admin.avatar')" type="file" />

                        <div class="d-flex justify-content-between align-items-end">
                            <x-admin.input identify="national_photo" title="تصویر کارت ملی" type="file" />
                            @if($user->national_photo)
                               <div class="mb-3">
                                   <a class="btn btn-success" target="_blank" href="{{ asset($user->national_photo) }}">دانلود</a>
                               </div>
                            @endif
                        </div>

                        <x-admin.checkbox identify="official_bill" description="درخواست فاکتور رسمی"  :old="$user->official_bill"/>

                        <x-admin.checkbox identify="is_block" description="دسترسی داشته باشد" :old="$user->is_block" />

                        <x-admin.button-submit title="{{ trans('panel.update') }}"/>

                        <x-admin.button-delete/>

                    </form>

                    <form id="deleteItem" action="{{ route('admin.user.destroy',$user->id) }}" method="post" class="form-inline">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    @include('admin.partial.ckeditor')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.user.index') }}');

            jalaliDatepicker.startWatch();

            stateCompanyContainer('{{ $user->person_type }}');
            $('#person_type').change(function (){
                stateCompanyContainer($(this).val());
            });

            stateIrnicContainer('{{ $user->irnic->status }}');
            $('#irnic_status').change(function (){
                stateIrnicContainer($(this).val());
            });

            makeInputOnlyAlpha($('#en_first_name'));
            makeInputOnlyAlpha($('#en_last_name'));
        })

        const companyContainer = $('#company_container');
        function stateCompanyContainer(status){
            if(status === '{{ \Modules\User\app\Enums\PersonType::Legal }}'){
                companyContainer.removeClass('d-none');
            }
            else{
                companyContainer.addClass('d-none');
            }
        }

        const irnicContainer = $('#irnic_container');
        function stateIrnicContainer(status){
            if(status === '{{ \Modules\User\app\Enums\IrnicStatus::HasIt }}'){
                irnicContainer.removeClass('d-none');
            }
            else{
                irnicContainer.addClass('d-none');
            }
        }
    </script>
@endsection
