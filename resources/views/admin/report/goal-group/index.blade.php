@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[\App\Enums\Assets\StyleLoader::DataTable()]])
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">گزارش اهداف گروهی</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">گزارش اهداف گروهی</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    @include('admin.partial.message')
                    <form method="get" action="{{ route('admin.goal.report.group') }}">
                        <div class="row mb-3">

                            <div class="col-12 col-md-3">
                                <x-select-month/>
                            </div>

                            <div class="col-12 col-md-3">
                                <x-select-month title="تا ماه" value="gregorian_end" identify="end_at"/>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12 col-md-3">
                                <x-admin.button-submit type="text" identify="s" title="اعمال فیلتر"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">نتایج جستجو</h3>
                    <button type="button" class="btn btn-success btn-sm">خروجی اکسل</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border text-nowrap text-md-nowrap table-striped mb-0">
                            <thead>
                            <tr>
                                <th>تاریخ شروع</th>
                                <th>تاریخ پایان</th>
                                <th>هدف گروهی فروش (ریال)</th>
                                <th>میزان گروهی فروش (ریال)</th>
                                <th>میزان پیشرفت</th>
                                <th>تعداد پروژه ها</th>
                                <th>لیست پروژه ها</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($goals as $goal)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ verta( $goal['start_at'])->format('F')  }}</span>
                                                <span>{{ verta( $goal['start_at'])->format('Y-m-d') }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ verta( $goal['end_at'])->format('F')  }}</span>
                                                <span>{{ verta( $goal['end_at'])->format('Y-m-d')  }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ number_format($goal['profitability']) }}</span>
                                                <span>${{ number_format($goal['profitability_dollar']) }}</span>
                                            </div>
                                        </td>
                                        <td>{{ number_format($goal['total_sales']) }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ calculatePercentageProgress($goal['total_sales'],$goal['profitability']) }}%</span>
                                                <span>{{ number_format($goal['total_sales'] - $goal['profitability']) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ number_format($goal['project_counts']) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-success" href="{{ route('admin.project.index',$goal['routeProjectParams']) }}"><span>نمایش</span></a>
                                        </td>
                                    </tr>
                                @endforeach
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
    @include('admin.partial.datatable_offline')
    <script>
        $(document).ready(function (){
            @if(request('start_at'))
                $('#start_at').val('{{ request('start_at') }}')
            @endif
            @if(request('start_at'))
                $('#end_at').val('{{ request('end_at') }}')
            @endif
        })
    </script>
@endsection
