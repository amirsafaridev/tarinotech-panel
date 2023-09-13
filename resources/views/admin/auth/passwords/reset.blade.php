@extends('admin.auth.master')
@section('title') {{ $title }} @endsection
@section('content')
    <h3 class="text-center">{{ trans('panel.auth.reset.reset') }}</h3>
    <form action="{{ route('admin.password.update') }}" method="post" class="forms-sample">
        @csrf



        <div class="mb-3">
            <label for="code" class="form-label">{{ trans('panel.auth.reset.code') }}</label>
            <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" placeholder="{{ trans('panel.auth.reset.code_placeholder') }}" name="code">
            @error('code')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ trans('panel.auth.reset.password') }}</label>
            <input id="password" type="password" class="form-control text-right @error('password') is-invalid @enderror" placeholder="{{ trans('panel.auth.reset.password_placeholder') }}" name="password">
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_rep" class="form-label">{{ trans('panel.auth.reset.password_rep') }}</label>
            <input id="password_rep" type="password" class="form-control text-right" placeholder="{{ trans('panel.auth.reset.password_rep_placeholder') }}" name="password_rep">
        </div>

        <div class="form-group mb-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <i class="la la-code form-icon"></i>
                        <label for="captcha" class="form-label">{{ trans('panel.auth.reset.captcha') }}</label>
                        <input class="form-control @error('captcha') is-invalid @enderror" type="text"  name="captcha" id="captcha" placeholder="{{ trans('panel.auth.reset.captcha_placeholder') }}">

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

        <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-success btn-md">{{ trans('panel.auth.reset.submit') }}</button>
            <a href="{{ route('admin.password.forget') }}">{{ trans('panel.auth.login.forget') }}</a>
        </div>

        @include('admin.partial.message')
    </form>
@endsection
