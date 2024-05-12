@extends('web.master')
@section('title') {{ $title }} @endsection
@section('head')
    <link rel="stylesheet" href="{{ asset('res-admin/assets/css/factor.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="col-12 col-md-6 mx-auto vh-100 px-4 d-flex flex-column justify-content-center align-items-center">

            <div class="card-factor factor">
                <div class="text-center mb-3">
                    <img src="{{ asset('res-admin/assets/images/brand/logo-dark.png') }}" class="header-brand-img" alt="{{ config('app.name') }}">
                </div>
                <h2 class="text-center title text-success mb-3 d-flex align-items-center gap-2">
                    <span class="text-success fa fa-check-circle icon-check"></span>
                    <span>   پرداخت شما با موفقیت انجام شد.</span>
                </h2>
                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <td>فاکتور</td>
                        <td>{{ $factor->title }}</td>
                    </tr>
                    <tr>
                        <td>شناسه فاکتور</td>
                        <td>{{ $factor->identify }}</td>
                    </tr>

                    <tr>
                        <td>مبلغ (ریال)</td>
                        <td>{{ number_format($factor->final_price) }} </td>
                    </tr>
                    <tr>
                        <td>تاریخ پرداخت</td>
                        <td>{{ $factor->paid_at?->toJalali()->format(formatJalaliDateTime()) }}</td>
                    </tr>
                    </tbody>
                </table>
                <a class="btn btn-success btn-block d-block" href="https://app.tarinotech.com">بازگشت به سایت</a>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
