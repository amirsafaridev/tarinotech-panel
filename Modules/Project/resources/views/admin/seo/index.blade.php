@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Datepicker(),
   ]])
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

    <form class="row" action="{{ route('admin.project.seo.index') }}">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">{{ $title }}</div>
                    @can('ADMIN_PROJECT_SEO_CREATE')
                        <a class="btn btn-primary" href="{{ route('admin.project.seo.create') }}">ایجاد</a>
                    @endcan
                </div>
                <div class="card-body">
                    @include('admin.partial.message')
                    @include('project::admin.seo.part.filter')
                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>
                                <td>شناسه</td>
                                <td>عنوان</td>
                                <td>کارشناس</td>
                                <td>پکیج</td>
                                <td>پرداخت ماهیانه</td>
                                <td>وضعیت</td>
                                @if($hasPricePermission)
                                    <td>قیمت</td>
                                @endif
                                <td>دامنه</td>
                                <td>ایجاد</td>
                                <td>عملیات</td>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <td>شناسه</td>
                                <td>عنوان</td>
                                <td>کارشناس</td>
                                <td>پکیج</td>
                                <td>پرداخت ماهیانه</td>
                                <td>وضعیت</td>
                                @if($hasPricePermission)
                                    <td>قیمت</td>
                                @endif
                                <td>دامنه</td>
                                <td>ایجاد</td>
                                <td>عملیات</td>
                            </tr>
                            </tfoot>

                            <tbody>
                            @if($projects->isNotEmpty())
                                @foreach($projects as $project)
                                    <tr>
                                        <td>{{ $project->id }}</td>
                                        <td>{{ $project->title }}</td>
                                        <td>{{ $project->admin_first_name }} {{ $project->admin_last_name }}</td>
                                        <td>{{ $project->packages_title }}</td>
                                        <td>{{ number_format($project->project_seo_price_monthly) }}</td>
                                        <td>{{ $project->project_statuses_title }}</td>
                                        @if($hasPricePermission)
                                            <td>{{ number_format($project->price) }}</td>
                                        @endif
                                        <td>{{ $project->domain }}</td>
                                        <td>{{ $project->created_at->toJalali()->format(formatJalaliDate()) }}</td>
                                        <td>

                                            <div class="dropdown">
                                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                    {{ __('panel.action.manage') }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('admin.project.seo.edit',$project->id) }}">{{ __('panel.action.edit') }}</a>
                                                    <a class="dropdown-item" href="{{ route('admin.project.manage',$project->id) }}">{{ __('panel.action.show') }}</a>
                                                    <a class="dropdown-item" href="{{ route('admin.project.seo.auto-factor',$project->id) }}">{{ __('panel.action.auto_factor') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
       \App\Enums\Assets\ScriptLoader::Datepicker(),
   ]])
    @include('admin.partial.script.global')
    <script>
        $(document).ready(function (){
            jalaliDatepicker.startWatch();
        })
    </script>
@endsection
