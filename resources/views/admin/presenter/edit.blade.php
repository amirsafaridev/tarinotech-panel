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
        <h1 class="page-title">مدیریت نمایندگان</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.presenter.index') }}">نمایندگان</a></li>
                <li class="breadcrumb-item active">ویرایش نماینده</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-4">
                    @include('admin.partial.message')
                    <form class="request-form forms-sample" method="post" action="{{ $routeUpdate }}">
                        @csrf
                        @method('PATCH')

                        <x-admin.input identify="mobile" title="شماره موبایل" :old="$user->mobile" :disabled="true"/>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="first_name" title="نام" :old="$user->first_name" />
                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="last_name" title="نام خانوادگی" :old="$user->last_name" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="email" title="پست الکترونیکی" type="email" :old="$user->email"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="tel" title="تلفن ثابت" :old="$user->tel"/>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label for="project_id" class="form-label">انتخاب پروژه</label>
                            <select  class="form-control" name="project_ids[]" id="project_id" multiple>
                                @if($user->accessProjects->isNotEmpty())
                                    @foreach($user->accessProjects as $project)
                                        <option selected value="{{$project->id}}" title="{{$project->title}}">{{$project->title}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>


                        <x-admin.checkbox identify="is_block" description="دسترسی داشته باشد" :old="$user->is_block" />

                        <x-admin.button-submit title="{{ trans('panel.update') }}"/>

                        <x-admin.button-delete/>
                    </form>

                    <form id="deleteItem" action="{{ $routeDestroy }}" method="post" class="form-inline">
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
    @include('admin.partial.share-script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Alert(),
        \App\Enums\Assets\ScriptLoader::Datepicker(),
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    <script>
        $(document).ready(function () {

            const dataPickerConfig = {
                format: 'YYYY-MM-DD',
                initialValueType: 'persian',
                initialValue: false,
                autoClose: true
            };

            $('#dob').persianDatepicker(dataPickerConfig);

            stateCompanyContainer('');
            $('#person_type').change(function (){
                stateCompanyContainer($(this).val());
            });

            stateIrnicContainer('');
            $('#irnic').change(function (){
                stateIrnicContainer($(this).val());
            });

            makeInputOnlyAlpha($('#en_first_name'));
            makeInputOnlyAlpha($('#en_last_name'));
            makeSelect2Remote($('#project_id'),'{{ route('admin.ajax.select2.project') }}',['title']);
        })

        const companyContainer = $('#company_container');
        function stateCompanyContainer(status){
            if(status === '{{ \App\Enums\Database\User\PersonType::Legal }}'){
                companyContainer.removeClass('d-none');
            }
            else{
                companyContainer.addClass('d-none');
            }
        }

        const irnicContainer = $('#irnic_container');
        function stateIrnicContainer(status){
            if(status === '{{ \App\Enums\Database\User\IrnicStatus::HasIt }}'){
                irnicContainer.removeClass('d-none');
            }
            else{
                irnicContainer.addClass('d-none');
            }
        }
    </script>
@endsection
