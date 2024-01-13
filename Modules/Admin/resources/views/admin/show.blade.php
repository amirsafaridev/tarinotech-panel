@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')

@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ trans('panel.admin.title') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.index') }}">{{ trans('panel.admin.title') }}</a></li>
                <li class="breadcrumb-item active">{{ trans('panel.admin.show') }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 col-xl-3 mb-3">
            <div class="card">
                <div class="card-header">
                    <span class="bold">اهداف فردی فروش</span>
                </div>
                <div class="card-body">
                    <p class="card-text mb-3">تعریف اهداف فردی فروش برای پرسنل</p>
                    <a href="{{ route('admin.admin.goal',$admin->id) }}" class="btn btn-success">ثبت</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3 mb-3">
            <div class="card">
                <div class="card-header">
                    <span class="bold">ورود ها</span>
                </div>
                <div class="card-body">
                    <p class="card-text mb-3">نمایش تاریخ ورود و خروج ها</p>
                    <a href="{{ route('admin.report.login',['user-type'=>'admin','user-id'=>$admin->id]) }}" class="btn btn-success">گزارش</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <td>آواتار</td>
                                <td>
                                    @if($admin->avatar)
                                        <img class="admin-avatar" src="{{ asset($admin->avatar) }}" alt="{{ $admin->first_name }} {{ $admin->last_name }}">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>شناسه</td>
                                <td><a href="{{ route('admin.admin.edit',$admin->id) }}">{{ $admin->id }}</a></td>
                            </tr>
                            <tr>
                                <td>نام</td>
                                <td>{{ $admin->first_name }}</td>
                            </tr>
                            <tr>
                                <td>نام خانوادگی</td>
                                <td>{{ $admin->last_name }}</td>
                            </tr>

                            <tr>
                                <td>سمت شغلی</td>
                                <td>{{ $admin->jobTitle->title }}</td>
                            </tr>

                            <tr>
                                <td>شماره همراه</td>
                                <td><a href="tel:{{ $admin->mobile }}">{{ $admin->mobile }}</a></td>
                            </tr>

                            <tr>
                                <td>شماره تماس</td>
                                <td><a href="tel:{{ $admin->tel }}">{{ $admin->tel }}</a></td>
                            </tr>

                            <tr>
                                <td>پست الکترونیک</td>
                                <td><a href="mailto:{{ $admin->email }}">{{ $admin->email }}</a></td>
                            </tr>

                            <tr>
                                <td>کد پستی</td>
                                <td>{{ $admin->postal_code }}</td>
                            </tr>

                            <tr>
                                <td>کد ملی</td>
                                <td>{{ $admin->national_code }}</td>
                            </tr>

                            <tr>
                                <td>محل انجام کار</td>
                                <td>{{ \App\Enums\Database\Admin\WorkLocation::getDescription($admin->work_location) }}</td>
                            </tr>

                            <tr>
                                <td>نوع بیمه</td>
                                <td>{{ \App\Enums\Database\Admin\TypeInsurance::getDescription($admin->type_insurance) }}</td>
                            </tr>

                            <tr>
                                <td>قرارداد دارد؟</td>
                                <td>@include('admin.partial.bool_badge',['value'=>$admin->has_contract])</td>
                            </tr>

                            <tr>
                                <td>اتباع خارجه</td>
                                <td>@include('admin.partial.bool_badge',['value'=>$admin->is_foreign_national])</td>
                            </tr>

                            <tr>
                                <td>سفته</td>
                                <td>{{ $admin->promissory }}</td>
                            </tr>

                            <tr>
                                <td>شبا</td>
                                <td>{{ $admin->shaba_number }}</td>
                            </tr>

                            <tr>
                                <td>شماره کارت</td>
                                <td>{{ $admin->cart_number }}</td>
                            </tr>

                            <tr>
                                <td>شماره همراه شرکتی</td>
                                <td>
                                    @if($admin->mobile_company)
                                        <a href="tel:{{ $admin->mobile_company }}">{{ $admin->mobile_company }}</a>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>شماره داخلی</td>
                                <td>
                                    @if($admin->number_company)
                                        <a href="tel:{{ $admin->number_company }}">{{ $admin->number_company }}</a>
                                    @endif
                                </td>
                            </tr>


                            @if($admin->dob)
                                <tr>
                                    <td>تاریخ تولد</td>
                                    <td>{{ $admin->dob->toJalali()->format('d F Y') }}</td>
                                </tr>
                            @endif

                            @if($admin->start_cooperation)
                                <tr>
                                    <td>تاریخ شروع همکاری</td>
                                    <td>{{ $admin->start_cooperation->toJalali()->format('d F Y') }}</td>
                                </tr>
                            @endif

                            @if($admin->start_last_contract)
                                <tr>
                                    <td>شروع آخرین قرارداد</td>
                                    <td>{{ $admin->start_last_contract->toJalali()->format('d F Y') }}</td>
                                </tr>
                            @endif


                            @if($admin->end_last_contract)
                                <tr>
                                    <td>پایان آخرین قرارداد</td>
                                    <td>{{ $admin->end_last_contract->toJalali()->format('d F Y') }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td>رزومه</td>
                                <td>{{ $admin->resume }}</td>
                            </tr>

                            <tr>
                                <td>توضیحات</td>
                                <td>{{ $admin->description }}</td>
                            </tr>

                            <tr>
                                <td>دسترسی</td>
                                <td>@include('admin.partial.bool_badge',['value'=>$admin->has_access])</td>
                            </tr>

                            <tr>
                                <td>سطح دسترسی</td>
                                <td>{{ $admin->roles()->get()->implode('name',',') }}</td>
                            </tr>

                            @if($admin->latestLogin)
                                <tr>
                                    <td>آخرین ورود</td>
                                    <td>{{ $admin->latestLogin->login_at->toJalali()->format('d F Y - H:i') }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td>تاریخ ایجاد</td>
                                <td>{{$admin->created_at->toJalali()->format('d F Y - H:i')}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.admin.index') }}');
        })
    </script>
@endsection
