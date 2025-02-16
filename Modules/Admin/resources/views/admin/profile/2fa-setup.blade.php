@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
       \App\Enums\Assets\StyleLoader::Toast(),
   ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.profile.index') }}">پروفایل</a></li>
                <li class="breadcrumb-item active">Google Two-Factor</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">فعالسازی احراز هویت دو مرحله‌ای</h4>
                    <p class="card-text">
                        لطفاً کد QR زیر را با اپلیکیشن Google Authenticator اسکن کنید.
                        در صورت بروز مشکل، می‌توانید کد دستی را وارد کنید.
                    </p>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        {!! $qrCodeSvg !!}
                    </div>
                    <p class="text-center">کد دستی: <strong>{{ $secretKey }}</strong></p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a class="btn btn-danger" href="{{ route('admin.admin.profile.disable2aGoogle') }}">
                        غیرفعالسازی
                    </a>
                    <a class="btn btn-secondary" href="{{ route('admin.admin.profile.index') }}">
                        بازگشت به پروفایل
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    @include('admin.partial.request')
@endsection
