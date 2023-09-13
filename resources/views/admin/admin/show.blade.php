@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')

@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ trans('panel.admin.title') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admin.index') }}">{{ trans('panel.admin.title') }}</a></li>
                <li class="breadcrumb-item active">{{ trans('panel.admin.show') }}</li>
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
                                <td>{{ trans('fields.admin.avatar') }}</td>
                                <td>
                                    @if($admin->avatar)
                                        <img class="admin-avatar" src="{{ asset($admin->avatar) }}" alt="{{ $admin->first_name }} {{ $admin->last_name }}">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>{{ trans('fields.admin.id') }}</td>
                                <td><a href="{{ route('admin.admin.edit',$admin->id) }}">{{ $admin->id }}</a></td>
                            </tr>
                            <tr>
                                <td>{{ trans('fields.admin.email') }}</td>
                                <td><a href="mailto:{{ $admin->email }}">{{ $admin->email }}</a></td>
                            </tr>
                            <tr>
                                <td>{{ trans('fields.admin.first_name') }}</td>
                                <td>{{ $admin->first_name }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('fields.admin.last_name') }}</td>
                                <td>{{ $admin->last_name }}</td>
                            </tr>

                            <tr>
                                <td>{{ trans('fields.admin.has_access') }}</td>
                                <td>@include('admin.partial.bool_badge',['value'=>$admin->has_access])</td>
                            </tr>

                            <tr>
                                <td>{{ trans('fields.admin.role') }}</td>
                                <td>{{ $admin->roles()->get()->implode('name',',') }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('fields.admin.created_at') }}</td>
                                <td>{{$admin->created_at->toJalali()->format('Y-m-d H:i')}}</td>
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
