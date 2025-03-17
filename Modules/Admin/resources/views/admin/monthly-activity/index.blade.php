@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style', [
        'load' => [\App\Enums\Assets\StyleLoader::DataTable()],
    ])
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">گزارش فعالیت ماهانه</h3>
                    <div class="month-navigation">
                        <a href="{{ route('admin.admin.monthly-activity.index', ['month' => 'prev']) }}"
                            class="btn btn-outline-primary btn-sm me-2">&lt;&lt;</a>
                        <span class="current-month">{{ $current_month }}</span>
                        <a href="{{ route('admin.admin.monthly-activity.index', ['month' => 'next']) }}"
                            class="btn btn-outline-primary btn-sm ms-2">&gt;&gt;</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>نام و نام خانوادگی</th>
                                    <th>میانگین کارکرد ساعتی/روزانه</th>
                                    <th>مجموع کارکرد طی ماه (ساعت)</th>
                                    <th>تایم فعالیت بیش از حد (دقیقه)</th>
                                    <th>غیبت تاخیر بیش از حد مجاز (دقیقه)</th>
                                    <th>ثبت ناقص فعالیت</th>
                                    <th>اقدامات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monthlyActivities as $activity)
                                    <tr>
                                        <td>{{ $activity['user']->fullname }}</td>
                                        <td>{{ $activity['average_daily_hours'] }}</td>
                                        <td>{{ $activity['total_monthly_hours'] }}</td>
                                        <td>{{ $activity['overtime_minutes'] ?: 'ندارد' }}</td>
                                        <td>{{ $activity['late_arrivals'] * 15 }}</td>
                                        <td>{{ $activity['incomplete_activities'] }} مورد</td>
                                        <td>
                                            <a href="{{ route('admin.admin.monthly-activity.daily-details', ['userId' => $activity['user']->id, 'startOfMonth' => $startOfMonth->format('Y-m-d'), 'endOfMonth' => $endOfMonth->format('Y-m-d')]) }}"
                                                class="btn btn-sm btn-info">
                                                مشاهده جزئیات روزهای ماه
                                            </a>
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

    <!-- Modal for daily details -->
    <div class="modal fade" id="dailyDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">جزئیات فعالیت روزانه</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="dailyDetailsContent"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@include('admin.partial.loader.script', ['load' => [\App\Enums\Assets\ScriptLoader::DataTable()]])
@include('admin.partial.datatable_offline')

    
@endsection

@section('style')
    @parent
    <style>
        .month-navigation {
            display: flex;
            align-items: center;
        }

        .current-month {
            font-size: 1rem;
            margin: 0 10px;
        }
    </style>
@endsection
