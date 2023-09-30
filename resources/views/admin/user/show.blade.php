@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')

@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">مدیریت مشتری ها</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">مشتری ها</a></li>
                <li class="breadcrumb-item active">نمایش مشتری</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-2">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <td>شناسه</td>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <td>موبایل</td>
                                <td>{{ $user->mobile }}</td>
                            </tr>
                            <tr>
                                <td>نام</td>
                                <td>{{ $user->first_name }}</td>
                            </tr>
                            <tr>
                                <td>نام خانوادگی</td>
                                <td>{{ $user->last_name }}</td>
                            </tr>
                            <tr>
                                <td>نام انگلیسی</td>
                                <td>{{ $user->en_first_name }}</td>
                            </tr>
                            <tr>
                                <td>نام خانوادگی انگلیسی</td>
                                <td>{{ $user->en_last_name }}</td>
                            </tr>
                            <tr>
                                <td>نام پدر</td>
                                <td>{{ $user->father_name }}</td>
                            </tr>
                            <tr>
                                <td>شماره ملی</td>
                                <td>{{ $user->national_id }}</td>
                            </tr>
                            <tr>
                                <td>شماره شناسنامه</td>
                                <td>{{ $user->document_id }}</td>
                            </tr>
                            <tr>
                                <td>تلفن</td>
                                <td>
                                    <a href="tel:{{ $user->tel }}">{{ $user->tel }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td>ایمیل</td>
                                <td>
                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td>عکس ملی</td>
                                <td>
                                    @if($user->national_photo)
                                        <a class="btn btn-success" href="{{ asset($user->national_photo) }}">دانلود</a>
                                    @else
                                        <span>ثبت نشده</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>عکس پروفایل</td>
                                <td>
                                    @if($user->avatar)
                                        <a class="btn btn-success" href="{{ asset($user->avatar) }}">دانلود</a>
                                    @else
                                        <span>ثبت نشده</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>تاریخ تولد</td>
                                <td>{{ $user->dob }}</td>
                            </tr>
                            <tr>
                                <td>نوع شخص</td>
                                <td>{{ \App\Enums\Database\User\PersonType::getDescription($user->person_type) }}</td>
                            </tr>
                            @if($user->person_type === \App\Enums\Database\User\PersonType::Legal)
                                <tr>
                                    <td>نام شرکت</td>
                                    <td>{{ $user->company->name }}</td>
                                </tr>
                                <tr>
                                    <td>نوع شرکت</td>
                                    <td>{{ \App\Enums\Database\Company\CompanyType::getDescription($user->company->type) }}</td>
                                </tr>
                                <tr>
                                    <td>شناسه ملی شرکت</td>
                                    <td>{{ $user->company->identify }}</td>
                                </tr>
                                <tr>
                                    <td>شماره ثبت شرکت</td>
                                    <td>{{ $user->company->register_id }}</td>
                                </tr>

                            @endif
                            <tr>
                                <td>صورتحساب رسمی</td>
                                <td>
                                    @include('admin.partial.bool_badge',['value' => $user->official_bill])
                                </td>
                            </tr>

                            <tr>
                                <td>تایید شده در</td>
                                <td>
                                @if($user->verify_at)
                                    <span>{{ $user->verify_at->toJalali()->format('d Y m') }}</span>
                                @else
                                    <span>تایید نشده</span>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>بلاک شده</td>
                                <td>
                                @include('admin.partial.bool_badge',['value' => $user->is_block])
                                </td>
                            </tr>
                            <tr>
                                <td>نوع کاربر</td>
                                <td>{{ \App\Enums\Database\User\UserType::getDescription($user->user_type) }}</td>
                            </tr>

                            <tr>
                                <td>آدرس</td>
                                <td>{{ $user->address->address }}</td>
                            </tr>
                            <tr>
                                <td>کد پستی</td>
                                <td>{{ $user->address->postal_code }}</td>
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

@endsection
