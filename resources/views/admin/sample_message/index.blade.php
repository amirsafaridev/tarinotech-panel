@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[\App\Enums\Assets\StyleLoader::DataTable()]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">پیام های آماده</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">پیام های آماده</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">پیام های آماده</h3>
                    <a class="btn btn-success btn-sm" href="{{ route('admin.sample-message.create') }}">ایجاد</a>
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>
                                @foreach($selects as $select)
                                    <th>{{ trans('datatable.'.$select)}}</th>
                                @endforeach
                                <th>{{ trans('datatable.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                @foreach($selects as $select)
                                    <th>{{ trans('datatable.'.$select)}}</th>
                                @endforeach
                                <th>{{ trans('datatable.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[\App\Enums\Assets\ScriptLoader::DataTable()]])
    @include('admin.partial.datatable')
@endsection
