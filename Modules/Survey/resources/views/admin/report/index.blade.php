@php use App\Enums\Assets\StyleLoader; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        StyleLoader::Toast(),
   ]])
    <style>
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .completion-progress {
            height: 25px;
            border-radius: 12px;
        }
    </style>
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}: {{ $survey->title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">پرسشنامه ها</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.survey.edit', $survey->id) }}">{{ $survey->title }}</a></li>
                <li class="breadcrumb-item active">گزارش</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <!-- Report actions -->
        <div class="col-xl-12">
            @include('admin.partial.message')
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="mb-3 mb-md-0">
                            <h4 class="mb-1">نمای کلی گزارش پرسشنامه</h4>
                            <p class="text-muted mb-0">
                                آخرین به‌روزرسانی: {{ now()->toJalali()->format(formatJalaliDateTime()) }}
                            </p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.survey.report.summary', $survey->id) }}" class="btn btn-primary">
                                <i class="fa fa-table-list me-1"></i> خلاصه نتایج
                            </a>
                            <a href="{{ route('admin.survey.report.charts', $survey->id) }}" class="btn btn-success">
                                <i class="fa fa-chart-pie me-1"></i> نمودارها
                            </a>
                            <a href="{{ route('admin.survey.report.export', $survey->id) }}" class="btn btn-secondary">
                                <i class="fa fa-file-excel me-1"></i> خروجی اکسل
                            </a>
                            <a href="{{ route('admin.survey.response.index', $survey->id) }}" class="btn btn-info">
                                <i class="fa fa-reply-all me-1"></i> مشاهده پاسخ‌ها
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key stats cards -->
        <div class="col-xl-12">
            <div class="row">
                <div class="col-sm-3">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                        <span class="avatar avatar-md bg-primary-transparent me-3">
                            <i class="fa fa-users"></i>
                        </span>
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $stats['total_responses'] }}</h5>
                                    <p class="text-muted mb-0 small">کل پاسخ‌ها</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                        <span class="avatar avatar-md bg-success-transparent me-3">
                            <i class="fa fa-check-circle"></i>
                        </span>
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $stats['completion_rate'] }}%</h5>
                                    <p class="text-muted mb-0 small">نرخ تکمیل</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                        <span class="avatar avatar-md bg-warning-transparent me-3">
                            <i class="fa fa-question-circle"></i>
                        </span>
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $stats['total_questions'] }}</h5>
                                    <p class="text-muted mb-0 small">تعداد سوالات</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                        <span class="avatar avatar-md bg-info-transparent me-3">
                            <i class="fa fa-clipboard-check"></i>
                        </span>
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $stats['average_answers_per_response'] }}</h5>
                                    <p class="text-muted mb-0 small">میانگین پاسخ‌ها</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Response trend chart -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">روند پاسخ‌ها</h3>
                </div>
                <div class="card-body">
                    @if(empty($responseTrend))
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            هنوز داده‌ای برای نمایش نمودار وجود ندارد.
                        </div>
                    @else
                        <div class="chart-container">
                            <canvas id="responseTrendChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Response stats -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">آمار پاسخ‌ها</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>تاریخ اولین پاسخ:</span>
                            <span class="fw-bold">
                                @if($stats['first_response_date'])
                                    {{ $stats['first_response_date']->toJalali()->format(formatJalaliDateTime()) }}
                                @else
                                    -
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>تاریخ آخرین پاسخ:</span>
                            <span class="fw-bold">
                                @if($stats['last_response_date'])
                                    {{ $stats['last_response_date']->toJalali()->format(formatJalaliDateTime()) }}
                                @else
                                    -
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>میانگین زمان پاسخگویی:</span>
                            <span class="fw-bold">{{ $stats['average_response_time'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>نرخ پاسخدهی سوالات اجباری:</span>
                            <span class="fw-bold">{{ $stats['completion_rate'] }}%</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>میانگین تعداد پاسخ‌ها به هر سوال:</span>
                            <span class="fw-bold">{{ $stats['average_answers_per_response'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Top questions by response rate -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">سوالات با بیشترین نرخ پاسخدهی</h3>
                </div>
                <div class="card-body">
                    @if($topQuestions->isEmpty())
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            هنوز داده‌ای برای نمایش وجود ندارد.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>سوال</th>
                                    <th>تعداد پاسخ‌ها</th>
                                    <th>نرخ پاسخدهی</th>
                                    <th>وضعیت</th>
                                    <th>جزئیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($topQuestions as $question)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-2">
                                                    <p class="mb-0 text-truncate"
                                                       style="max-width: 250px;">{{ $question['text'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $question['answers_count'] }}</td>
                                        <td>
                                            <div class="progress completion-progress">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                     style="width: {{ $question['completion_rate'] }}%;"
                                                     aria-valuenow="{{ $question['completion_rate'] }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">{{ $question['completion_rate'] }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($question['is_required'])
                                                <span class="badge bg-danger">اجباری</span>
                                            @else
                                                <span class="badge bg-info">اختیاری</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.survey.report.question', [$survey->id, $question['id']]) }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bottom questions by response rate -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">سوالات با کمترین نرخ پاسخدهی</h3>
                </div>
                <div class="card-body">
                    @if($bottomQuestions->isEmpty())
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            هنوز داده‌ای برای نمایش وجود ندارد.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>سوال</th>
                                    <th>تعداد پاسخ‌ها</th>
                                    <th>نرخ پاسخدهی</th>
                                    <th>وضعیت</th>
                                    <th>جزئیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($bottomQuestions as $question)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-2">
                                                    <p class="mb-0 text-truncate"
                                                       style="max-width: 250px;">{{ $question['text'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $question['answers_count'] }}</td>
                                        <td>
                                            <div class="progress completion-progress">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                     style="width: {{ $question['completion_rate'] }}%;"
                                                     aria-valuenow="{{ $question['completion_rate'] }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">{{ $question['completion_rate'] }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($question['is_required'])
                                                <span class="badge bg-danger">اجباری</span>
                                            @else
                                                <span class="badge bg-info">اختیاری</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.survey.report.question', [$survey->id, $question['id']]) }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        ScriptLoader::Chart(),
    ]])
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.survey.index') }}');

            @if(!empty($responseTrend))
            // Response trend chart
            const trendCtx = document.getElementById('responseTrendChart').getContext('2d');
            const trendData = @json($responseTrend);

            const dates = trendData.map(item => item.jalali_date);
            const counts = trendData.map(item => item.count);

            const trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'تعداد پاسخ‌ها',
                        data: counts,
                        borderColor: '#5d87ff',
                        backgroundColor: 'rgba(93, 135, 255, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
            @endif
        });
    </script>
@endsection
