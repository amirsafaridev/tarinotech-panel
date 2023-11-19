@extends('login::admin.master')
@section('title') {{ $title }} @endsection
@section('content')
    <h3 class="text-center">{{ trans('panel.auth.login.login') }}</h3>
    <form action="{{ route('auth.admin.login') }}" method="post" class="forms-sample">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">{{ trans('panel.auth.login.email') }}</label>
            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" placeholder="{{ trans('panel.auth.login.email_placeholder') }}" name="email" value="{{ old('email') }}"  autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ trans('panel.auth.login.password') }}</label>
            <input id="password" type="password" class="form-control text-right @error('password') is-invalid @enderror" placeholder="{{ trans('panel.auth.login.password_placeholder') }}" name="password">
            @error('password')
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
                        <label for="captcha" class="form-label">{{ trans('panel.auth.login.captcha') }}</label>
                        <input class="form-control @error('captcha') is-invalid @enderror" type="text"  name="captcha" id="captcha" placeholder="{{ trans('panel.auth.login.captcha_placeholder') }}">

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

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="remember" name="remember"  {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                <span>{{ trans('panel.auth.login.remember') }}</span>
            </label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-success btn-md">{{ trans('panel.auth.login.submit') }}</button>
            <a href="{{ route('auth.admin.password.forget') }}">{{ trans('panel.auth.login.forget') }}</a>
        </div>
    </form>
@endsection
