@extends('auth::admin.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <h3 class="text-center">کد تایید</h3>
    <form action="{{ route('auth.admin.verify') }}" method="post" class="forms-sample">
        @csrf

        <div class="mb-3">
            <input type="text" class="form-control text-center @error('code') is-invalid @enderror"
                   name="code" autofocus>
            @error('code')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="d-flex flex-column gap-3">
            @include('admin.partial.message')
            <button class="btn btn-success mb-2 btn-md">تایید و ورود</button>
            <a class="text-center" href="{{ route('auth.admin.login') }}">{{ trans('panel.auth.login.login') }}</a>
        </div>
    </form>
@endsection
