@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[\App\Enums\Assets\StyleLoader::DataTable()]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">مبالغ ثابت</h3>
                    @can('ADMIN_ADMIN_FIXED_AMOUNT_CREATE')
                        <a class="btn btn-success btn-sm" href="{{ route('admin.admin.fixed-amount.create') }}">ایجاد درخواست مبلغ ثابت</a>
                    @endcan
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>

                                <th>شناسه</th>
                                <th>پایه حقوق(مبلغ کل واحد پایه)</th>
                                <th>حق مسکن</th>
                                <th>حق تأهل</th>
                                <th>حق اولاد</th>
                                <th>حق خوار و بار</th>
                                <th>بیمه سهم کارفرما(حضوری)</th>
                                <th>بیمه سهم پرسنل(حضوری)</th>
                                <th>بیمه سهم کارفرما(دورکاری)</th>
                                <th>بیمه سهم پرسنل(دورکاری)</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($fixedAmounts->isNotEmpty())
                                @foreach($fixedAmounts as $fixedAmount)
                                    <tr>
                                      
                                        <td>{{ $fixedAmount->id }}</td>


                                        <td>{{ $fixedAmount->basic_rights }}</td>
                                        <td>{{ $fixedAmount->right_to_housing }}</td>
                                        <td>{{ $fixedAmount->right_to_marry }}</td>

                                        <td>{{ $fixedAmount->childrens_right }}</td>
                                        <td>{{ $fixedAmount->right_to_eat_and_drink }}</td>
                                        <td>{{ $fixedAmount->employer_insurance }}</td>
                                        <td>{{ $fixedAmount->personnel_insurance }}</td>
                                        <td>{{ $fixedAmount->employer_insurance_remote }}</td>
                                        <td>{{ $fixedAmount->personnel_insurance_remote }}</td>

                                     
                                        <td>
                                            <a href="{{ route('admin.admin.fixed-amount.edit',$fixedAmount->id) }}" class="btn btn-warning btn-sm">ویرایش</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[\App\Enums\Assets\ScriptLoader::DataTable()]])
    @include('admin.partial.datatable_offline')
@endsection
