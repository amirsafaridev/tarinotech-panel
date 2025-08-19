@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', ['load' => [\App\Enums\Assets\StyleLoader::Toast()]])
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
                <li class="breadcrumb-item active">مدیریت پروژه سئو</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <form class="request-form row forms-sample" method="post"
            action="{{ route('admin.project.seo.update.status', $project->id) }}">
            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                @include('admin.partial.message')
                @csrf
                @method('PATCH')

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">ویرایش وضعیت</h3>
                        <div class="card-options">
                            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                                    class="fal fa-chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="card-body pb-4">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <input type="hidden" name="type_id" value="{{ $project->type_id }}">
                                <x-admin.select-model identify="type_id" title="نوع پروژه" :items="$types"
                                    :old="$project->type_id" :has-choice-option="false" disabled key="id" value="title" />
                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.select-simple identify="status_id" title="وضعیت پروژه" />
                            </div>


                        </div>

                        <x-admin.button title="{{ trans('panel.update') }}" />

                    </div>
                </div>
            </div>
        </form>

        <form class="request-form row forms-sample" method="post"
            action="{{ route('admin.project.project_facilities.store', ['project' => $project->id, 'type' => 'seo']) }}">
            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                @csrf
                @method('POST')

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">افزودن امکان چانبی</h3>
                        <div class="card-options">
                            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                                    class="fal fa-chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="card-body pb-4">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <x-admin.select-model identify="facilities[]" title="ویژگی‌ها" key="id" value="title"
                                    :multiple="true" :with-option="false" :items="$facilities" />

                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.select-model identify="user_id" title="انتخاب کارشناس" :items="$users"
                                    key="id" value="fullname" />

                            </div>
                            <div class="col-12 col-md-6">
                                <x-admin.input identify="renewal_at" title="تاریخ تمدید" :is-date-picker="true" />

                            </div>


                        </div>

                        <x-admin.button title="افزودن" />

                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="bold">واحدهای کل</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>عنوان</th>
                            <th>نوع واحد</th>
                            <th>توع</th>
                            <th>میزان واحد اکسترای کارفرما EUC</th>
                            <th>کارشناس مربوطه</th>
                            <th>وضغیت فاکتور</th>
                            <th>شناسه فاکتور</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $project->target->package->title }}</td>
                            <td>واحد اصلی</td>
                            <td>پکیج اصلی</td>
                            <td>{{ !empty($project->target->package->main_unit) ? $project->target->package->main_unit : '-' }}
                            </td>

                            <td>{{ $project->admin->first_name }} {{ $project->admin->last_name }}</td>
                            <td>-</td>
                            <td>-</td>

                        </tr>
                        @foreach ($project->facilities()->get() as $item)
                            @php
                                $factor = $item->factors()->where('project_id', $project->id)->first();
                            @endphp

                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>واحد اکسترا</td>
                                <td>امکانات جانبی</td>
                                <td>{{ !empty($item->customer_extra_unit) ? $item->customer_extra_unit : '-' }}
                                </td>
                                <td>{{ \Modules\Admin\app\Models\Admin::find($item->pivot->user_id)?->fullname ?? '-' }}
                                </td>
                              <td>{!! !empty($factor->status) ? factorStatusRender($factor->status, $factor->is_confirm) : '-' !!}</td>
                                <td>{{ $factor->identify ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    @php
                        $extraUnitTotal = $project->facilities()->sum('customer_extra_unit');
                        $mainUnit = $project->target->package->main_unit;
                        $totalUnits = $mainUnit + $extraUnitTotal;
                    @endphp
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>مجموع واحد اکسترا:</strong></td>
                            <td>{{ number_format($extraUnitTotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>مجموع واحد کل:</strong></td>
                            <td>{{ number_format($totalUnits, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


    {{-- @include('log::admin.part.table',['logTitle'=>'تست','itemsProperties'=>$statuses]) --}}
@endsection
@section('script')
    @include('admin.partial.loader.script', ['load' => [\App\Enums\Assets\ScriptLoader::Select2()]])
    @include('admin.partial.request')
    @include('admin.partial.script.global')
    @include('project::admin.web.part.script-status')

    <script>
        $(document).ready(function() {
            $('#facilities').select2()
            activeParentUl('{{ route('admin.project.seo.index') }}');
        })
    </script>
@endsection
