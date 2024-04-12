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
                <h2 class="text-center title text-danger mb-3 d-flex align-items-center gap-2">
                    <span class="text-danger fa fa-close icon-check"></span>
                    <span>خطایی پیش آمده است!</span>
                </h2>
                <div class="alert alert-danger">
                    <div>{{ $message ?? '' }}</div>
                </div>
            </div>
        </div>


    </div>
@endsection
@section('script')

@endsection
