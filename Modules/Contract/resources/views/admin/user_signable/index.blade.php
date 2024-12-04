@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <form class="row" action="{{ route('admin.contract.sign.user.index') }}">
        <div class="col-xl-12 col-lg-12">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    @include('contract::admin.part.user-filter')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>
                                <td>شناسه پروژه</td>
                                <td>کارشناس</td>
                                <td>پروژه</td>
                                <td>دامنه</td>
                                <td>کارفرما</td>
                                <td>وضعیت</td>
                                <td>تاریخ ایجاد</td>
                                <td>عملیات</td>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <td>شناسه پروژه</td>
                                <td>کارشناس</td>
                                <td>پروژه</td>
                                <td>دامنه</td>
                                <td>کارفرما</td>
                                <td>وضعیت</td>
                                <td>تاریخ ایجاد</td>
                                <td>عملیات</td>
                            </tr>
                            </tfoot>

                            <tbody>
                            @if($userSignables->isNotEmpty())
                                @foreach($userSignables as $signable)
                                    <tr>
                                        <td>{{ $signable->project_id }}</td>
                                        <td>{{ $signable->admin_first_name }} {{ $signable->admin_last_name }}</td>
                                        <td>{{ $signable->project_title }}</td>
                                        <td>{{ $signable->project_domain }}</td>
                                        <td>
                                            @if($signable->user_first_name && $signable->user_last_name)
                                                {{ $signable->user_first_name }} {{ $signable->user_last_name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{!! \App\Helpers\Helper::renderUserSignableStatus($signable->status) !!}</td>
                                        <td>{{ verta($signable->created_at)->format(formatJalaliDateTime()) }}</td>
                                        <td class="d-flex gap-2">
                                            <a class="btn btn-sm btn-info" target="_blank" href="{{ makeRouteContractPreview($signable->target_type, $signable->target_id) }}">{{ __('panel.action.printContract') }}</a>
                                            <a class="btn btn-sm btn-warning" target="_blank" href="{{ route('admin.contract.sign.user.edit', $signable->id) }}">{{ __('panel.action.edit') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $userSignables->links() }}
                    </div>

                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[\App\Enums\Assets\ScriptLoader::DataTable()]])
@endsection
