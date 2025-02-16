@extends('auth::admin.master')
@section('title') {{ $title }} @endsection
@section('content')
    <h3 class="text-center">{{ trans('panel.auth.forget.forget') }}</h3>
    <form action="{{ route('auth.admin.password.email') }}" method="post" class="forms-sample">
        @csrf
        <div class="mb-3">
            <label for="identify" class="form-label">{{ trans('panel.auth.forget.identify') }}</label>
            <input id="identify" type="text" class="form-control @error('identify') is-invalid @enderror" name="identify" value="{{ old('identify') }}"  autofocus>
            @error('identify')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <i class="la la-code form-icon"></i>
                        <label for="captcha" class="form-label">{{ trans('panel.auth.forget.captcha') }}</label>
                        <input class="form-control @error('captcha') is-invalid @enderror" type="text"  name="captcha" id="captcha" placeholder="{{ trans('panel.auth.forget.captcha_placeholder') }}">

                        @error('captcha')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <p class="text-center pt-3">
                        {!! captcha_img('auth-admin') !!}
                    </p>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <button class="btn btn-primary me-2 mb-2 mb-md-0 text-white">{{ trans('panel.auth.forget.send') }}</button>
        </div>

        @include('admin.partial.message')
    </form>
@endsection
