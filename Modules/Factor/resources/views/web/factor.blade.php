@extends('web.master')
@section('title') {{ $title ?? 'نتیجه تراکنش' }} @endsection
@section('head')
    <link rel="stylesheet" href="{{ asset('res-admin/assets/css/factor.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="col-12 col-md-6 mx-auto vh-100 px-4 d-flex flex-column justify-content-center align-items-center">

            <div class="card-factor factor">
                <h2 class="text-center text-gray-dark mb-3">پرداخت فاکتور</h2>
                @include('components.alert')
                <table class="table table-hover">
                    <tr>
                        <td>عنوان</td>
                        <td>{{ $factor->title }}</td>
                    </tr>
                    <tr>
                        <td>شناسه</td>
                        <td>{{ $factor->identify }}</td>
                    </tr>
                    <tr>
                        <td>تاریخ ایجاد</td>
                        <td>{{ $factor->created_at?->toJalali()->format(formatJalaliDateTime()) }}</td>
                    </tr>
                    <tr>
                        <td>مهلت پرداخت</td>
                        <td>{{ $factor->expired_at?->toJalali()->format(formatJalaliDateTime()) }}</td>
                    </tr>
                    @if($factor->project)
                        <tr>
                            <td>پروژه</td>
                            <td>{{ $factor->project->title }}</td>
                        </tr>
                    @endif

                    <tr>
                        <td>مبلغ (ریال)</td>
                        <td>{{ number_format($factor->final_price) }}</td>
                    </tr>
                </table>

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>مبلغ (ریال)</th>
                        <th>مالیات (ریال)</th>
                        <th>تخفیف (ریال)</th>
                        <th>قیمت نهایی (ریال)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($factor->items as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{{ number_format($item->price) }}</td>
                            <td>{{ number_format($item->tax_amount) }}</td>
                            <td>{{ number_format($item->discount) }}</td>
                            <td>{{ number_format($item->final_price) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($factor->status === \Modules\Factor\app\Enums\FactorStatus::Pending)
                    <a class="btn btn-success btn-lg w-100" href="{{ route('payment.pay',$factor->identify) }}">پرداخت آنلاین</a>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
