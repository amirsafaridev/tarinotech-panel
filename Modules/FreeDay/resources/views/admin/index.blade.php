@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[\App\Enums\Assets\StyleLoader::DataTable()]])
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

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">{{ $title }}</div>
                    <div>
                        <a class="btn btn-primary" href="{{ route('admin.free-day.create') }}">ایجاد</a>
                        <a class="btn btn-success" href="{{ route('admin.free-day.import.index') }}">Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>
                                @foreach ($dataTable['columns'] as $column)
                                    <th>{{ $column['as'] }}</th>
                                @endforeach
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                @foreach ($dataTable['columns'] as $column)
                                    <th>{{ $column['as'] }}</th>
                                @endforeach
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
    @include('admin.partial.datatable2')
@endsection
