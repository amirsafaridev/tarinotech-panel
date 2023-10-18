@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Select2(),
    ]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">پروژه ها - گوگل ادز</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">پروژه ها</a></li>
                <li class="breadcrumb-item active">گوگل ادز</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">گوگل ادز</h3>
                    <div class="card-options">
                        <a href="{{ route('admin.project.ads.create') }}" class="btn btn-success btn-sm">ایجاد پروژه</a>
                    </div>
                </div>

                <div class="card-body">
                    @include('admin.partial.message')

                    @include('admin.project.ads.part.filter')

                    @if($projects->isNotEmpty())
                        <div class="table-responsive">
                            <table id="data-table" class="table">
                                <thead>
                                <tr>
                                    <th>شناسه</th>
                                    <th>نام</th>
                                    <th>کارشناس</th>
                                    <th>کارفرما</th>
                                    <th>دامنه</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($projects as $project)
                                    <tr>
                                        <td>{{ $project->id }}</td>
                                        <td>{{ $project->title }}</td>
                                        <td>{{ $project->admin->first_name }} {{ $project->admin->last_name }}</td>
                                        <td>{{ $project->user->first_name }} {{ $project->user->last_name }}</td>
                                        <td>{{ $project->domain }}</td>
                                        <td>{{ $project->status->title }}</td>
                                        <td>{{ verta($project->created_at)->format(formatJalaliDate()) }}</td>
                                        <td>
                                            <a target="_blank" class="btn btn-warning btn-sm" href="{{ route('admin.project.ads.edit',$project->id) }}">ویرایش</a>
                                            <a target="_blank" class="btn btn-info btn-sm" href="{{ route('admin.project.ads.show',$project->id) }}">نمایش</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $projects->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p>پروژه ای یافت نشد!</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Select2(),
    ]])
    <script>
        $(document).ready(function (){
            $('#package_id').select2();
            $('#status_id').select2();
        });
    </script>
@endsection
