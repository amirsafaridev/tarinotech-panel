@php use App\Enums\Assets\StyleLoader; @endphp
@php use App\Enums\Assets\ScriptLoader; @endphp
@php use Modules\Survey\app\Enums\Database\QuestionTypeEnum; @endphp
@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        StyleLoader::Toast(),
   ]])
    <style>
        .chart-container {
            position: relative;
            height: 350px;
            margin-bottom: 20px;
        }

        .chart-container-small {
            position: relative;
            height: 250px;
            margin-bottom: 20px;
        }

        .option-badge {
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 5px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: #fff;
        }

        .stats-card {
            transition: all 0.3s;
            border-radius: 10px;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">پرسشنامه ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.edit', $survey->id) }}">{{ $survey->title }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.report.index', $survey->id) }}">گزارش</a></li>
                <li class="breadcrumb-item active">نمودارهای پرسشنامه</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <!-- Overview card -->
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="mb-3 mb-md-0">
                            <h4 class="mb-0">{{ $survey->title }}</h4>
                            <p class="text-muted mb-0">
                                تعداد پاسخ‌ها: <span class="fw-bold">{{ $stats['total_responses'] }}</span> |
                                میزان تکمیل: <span class="fw-bold">{{ $stats['completion_rate'] }}%</span> |
                                تعداد سوالات: <span class="fw-bold">{{ $stats['total_questions'] }}</span>
                            </p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.survey.report.index', $survey->id) }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-right me-1"></i> بازگشت به صفحه گزارش
                            </a>
                            <a href="{{ route('admin.survey.report.export', $survey->id) }}" class="btn btn-primary">
                                <i class="fa fa-file-excel me-1"></i> خروجی اکسل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Response trend chart -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">روند پاسخ‌ها در طول زمان</h3>
                </div>
                <div class="card-body">
                    @if(count($responseTrend) > 0)
                        <div class="chart-container">
                            <canvas id="responseTrendChart"></canvas>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            اطلاعات کافی برای نمایش نمودار روند پاسخ‌ها وجود ندارد.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics cards -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">آمار کلی پرسشنامه</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-xl-12">
                            <div class="card bg-primary text-white stats-card mb-4">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fa fa-users fa-2x opacity-75"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $stats['total_responses'] }}</h5>
                                            <p class="mb-0 opacity-75">پاسخ‌های دریافتی</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-12">
                            <div class="card bg-success text-white stats-card mb-4">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fa fa-chart-pie fa-2x opacity-75"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $stats['completion_rate'] }}%</h5>
                                            <p class="mb-0 opacity-75">نرخ تکمیل پرسشنامه</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-2 mb-3">جزئیات بیشتر</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>تعداد سوالات:</span>
                            <span class="fw-bold">{{ $stats['total_questions'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>میانگین پاسخ به هر سوال:</span>
                            <span class="fw-bold">{{ $stats['average_answers_per_response'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>اولین پاسخ:</span>
                            <span class="fw-bold">{{ $stats['first_response_date'] ? $stats['first_response_date']->toJalali()->format(formatJalaliDate()) : 'ندارد' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>آخرین پاسخ:</span>
                            <span class="fw-bold">{{ $stats['last_response_date'] ? $stats['last_response_date']->toJalali()->format(formatJalaliDate()) : 'ندارد' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Response by hour chart -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">توزیع پاسخ‌ها بر اساس ساعت</h3>
                </div>
                <div class="card-body">
                    @if(count($responsesByHour) > 0)
                        <div class="chart-container">
                            <canvas id="hourlyResponseChart"></canvas>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            اطلاعات کافی برای نمایش نمودار توزیع ساعتی وجود ندارد.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Question completion rate chart -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">نرخ تکمیل سوالات</h3>
                </div>
                <div class="card-body">
                    @if(count($questionCompletionRates) > 0)
                        <div class="chart-container">
                            <canvas id="questionCompletionChart"></canvas>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            اطلاعات کافی برای نمایش نمودار نرخ تکمیل سوالات وجود ندارد.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Choice distribution for each question -->
        @if(count($choiceDistribution) > 0)
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">توزیع پاسخ‌ها برای سوالات چندگزینه‌ای</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($choiceDistribution as $index => $question)
                                <div class="col-md-6 col-xl-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header py-2">
                                            <h5 class="card-title mb-0">{{ $question['text'] }}</h5>
                                            <p class="text-muted mb-0 small">تعداد انتخاب: {{ $question['total'] }}</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="chart-container-small">
                                                <canvas id="questionChart{{ $index }}"></canvas>
                                            </div>

                                            <div class="mt-3">
                                                @foreach($question['options'] as $option)
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="option-badge" style="background-color: {{ $option['color'] }}">
                                                            {{ $option['text'] }}
                                                        </span>
                                                        <span class="fw-bold">{{ $option['count'] }} ({{ $option['percentage'] }}%)</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        ScriptLoader::Chart(),
    ]])
    <script>
        $(document).ready(function () {
            activeParentUl('{{ route('admin.survey.index') }}');

            // Response trend chart
            @if(count($responseTrend) > 0)
            const trendCtx = document.getElementById('responseTrendChart').getContext('2d');
            const trendData = {
                labels: [@foreach($responseTrend as $data) '{{ $data['jalali_date'] }}', @endforeach],
                datasets: [{
                    label: 'تعداد پاسخ‌ها',
                    data: [@foreach($responseTrend as $data) {{ $data['count'] }}, @endforeach],
                    borderColor: '#5d87ff',
                    backgroundColor: 'rgba(93, 135, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            };

            new Chart(trendCtx, {
                type: 'line',
                data: trendData,
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

            // Hourly response chart
            @if(count($responsesByHour) > 0)
            const hourlyCtx = document.getElementById('hourlyResponseChart').getContext('2d');
            const hourlyData = {
                labels: [@foreach($responsesByHour as $data) '{{ $data['display'] }}', @endforeach],
                datasets: [{
                    label: 'تعداد پاسخ‌ها',
                    data: [@foreach($responsesByHour as $data) {{ $data['count'] }}, @endforeach],
                    backgroundColor: 'rgba(255, 145, 73, 0.7)',
                    borderColor: '#ff9149',
                    borderWidth: 1
                }]
            };

            new Chart(hourlyCtx, {
                type: 'bar',
                data: hourlyData,
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
                            display: false
                        }
                    }
                }
            });
            @endif

            // Question completion rate chart
            @if(count($questionCompletionRates) > 0)
            const completionCtx = document.getElementById('questionCompletionChart').getContext('2d');
            const completionData = {
                labels: [@foreach($questionCompletionRates->take(10) as $question) '{{ Str::limit($question['text'], 20) }}', @endforeach],
                datasets: [{
                    label: 'نرخ تکمیل (%)',
                    data: [@foreach($questionCompletionRates->take(10) as $question) {{ $question['completion_rate'] }}, @endforeach],
                    backgroundColor: [@foreach($questionCompletionRates->take(10) as $question)
                        @if($question['is_required']) 'rgba(220, 53, 69, 0.7)' @else 'rgba(32, 201, 151, 0.7)' @endif,
                        @endforeach],
                    borderColor: [@foreach($questionCompletionRates->take(10) as $question)
                        @if($question['is_required']) '#dc3545' @else '#20c997' @endif,
                        @endforeach],
                    borderWidth: 1
                }]
            };

            new Chart(completionCtx, {
                type: 'horizontalBar',
                data: completionData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw + '%';
                                }
                            }
                        }
                    }
                }
            });
            @endif

            // Charts for each question
            @foreach($choiceDistribution as $index => $question)
            const ctx{{ $index }} = document.getElementById('questionChart{{ $index }}').getContext('2d');
            new Chart(ctx{{ $index }}, {
                type: 'pie',
                data: {
                    labels: [@foreach($question['options'] as $option) '{{ $option['text'] }}', @endforeach],
                    datasets: [{
                        data: [@foreach($question['options'] as $option) {{ $option['count'] }}, @endforeach],
                        backgroundColor: [@foreach($question['options'] as $option) '{{ $option['color'] }}', @endforeach],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.formattedValue || '';
                                    const dataset = context.dataset;
                                    const total = dataset.data.reduce((acc, data) => acc + data, 0);
                                    const percentage = Math.round((context.raw / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
            @endforeach
        });
    </script>
@endsection
