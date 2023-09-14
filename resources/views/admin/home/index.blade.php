@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')

@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ trans('panel.dashboard.title') }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-2">
            <h1>Deploy</h1>
            @include('admin.partial.message')
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-order">
                        <h2 class="text-end">
                            <i class="fal fa-user icon-size float-start text-danger text-danger-shadow p-3"></i>
                            <span>{{ number_format($data['admins_count']) }}</span>
                        </h2>
                        <div class="mb-0 pt-5">
                            <span>{{ trans('panel.dashboard.total_admin') }}</span>
                            <span class="float-end">
                                <a class="btn btn-sm btn-light" href="{{ route('admin.admin.index') }}">{{ trans('panel.dashboard.show_link') }}</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- COL END -->
    </div>

    <div class="row">

    </div>
@endsection
@section('script')

@endsection
