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
                <li class="breadcrumb-item active">ویرایش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body pb-3">
                    <form class="request-form forms-sample" method="post" action="{{ route('admin.admin.profile.update',$admin->id) }}">
                        @csrf
                        @method('PATCH')

                        <img class="admin-avatar" src="{{ $admin->avatar ? asset($admin->avatar) : asset('uploads/admin/avatar.png') }}" alt="{{ $admin->first_name }} {{ $admin->last_name }}">

                        <x-admin.input identify="avatar" :title="trans('fields.admin.avatar')" type="file" />

                        <x-admin.input identify="email" :title="trans('fields.admin.email')" :old="$admin->email" readonly="readonly" disabled />

                        <x-admin.input identify="first_name" :title="trans('fields.admin.first_name')" :old="$admin->first_name" />

                        <x-admin.input identify="last_name" :title="trans('fields.admin.last_name')" :old="$admin->last_name" />

                        <x-admin.button-submit title="{{ trans('panel.update') }}"/>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    @include('admin.partial.request')
@endsection
