@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[]])
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

    <form class="row" action="{{ route('admin.project.renewal.index') }}">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    @include('admin.partial.message')
                    @include('project::admin.renewal.part.filter')

                    <div class="table-responsive">
                        <table id="data-table" class="table">
                            <thead>
                            <tr>
                                <td>شناسه</td>
                                <td>پروژه</td>
                                <td>کارشناس</td>
                                <td>دامنه</td>
                                <td>تاریخ قرارداد</td>
                                <td>تاریخ تمدید</td>
                                <td>وضعیت</td>
                                <td>ایجاد</td>
                                <td>عملیات</td>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <td>شناسه</td>
                                <td>پروژه</td>
                                <td>کارشناس</td>
                                <td>دامنه</td>
                                <td>تاریخ قرارداد</td>
                                <td>تاریخ تمدید</td>
                                <td>وضعیت</td>
                                <td>ایجاد</td>
                                <td>عملیات</td>
                            </tr>
                            </tfoot>

                            <tbody>
                            @if($renewals->isNotEmpty())
                                @foreach($renewals as $renewal)
                                    <tr>
                                        <td>{{ $renewal->project_id }}</td>
                                        <td>{{ $renewal->project_title }}</td>
                                        <td>{{ $renewal->admin_first_name }} {{ $renewal->admin_last_name }}</td>
                                        <td>{{ $renewal->project_domain }}</td>
                                        <td>{{ verta($renewal->project_agreement_at)->format(formatJalaliDate()) }}</td>
                                        <td>{{ verta($renewal->created_at)->format(formatJalaliDate()) }}</td>
                                        <td>{!! \App\Helpers\Helper::renderProjectRenewalStatus($renewal->status) !!}</td>
                                        <td>{{ verta($renewal->project_renewal_at)->format(formatJalaliDate()) }}</td>
                                        <td class="d-flex gap-2">
                                            <a class="btn btn-sm btn-info" target="_blank" href="{{ route('admin.project.renewal.show',$renewal->id) }}">{{ __('panel.action.show') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $renewals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[]])
@endsection
