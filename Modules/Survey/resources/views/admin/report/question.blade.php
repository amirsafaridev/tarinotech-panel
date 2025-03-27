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

        .text-answer {
            border-right: 3px solid #5d87ff;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .text-answer:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .word-cloud-item {
            display: inline-block;
            margin: 5px;
            padding: 5px 10px;
            border-radius: 20px;
            background-color: #5d87ff;
            color: white;
            font-size: 14px;
        }

        .word-cloud-item.size-1 {
            font-size: 14px;
            opacity: 0.7;
        }

        .word-cloud-item.size-2 {
            font-size: 16px;
            opacity: 0.8;
        }

        .word-cloud-item.size-3 {
            font-size: 18px;
            opacity: 0.9;
        }

        .word-cloud-item.size-4 {
            font-size: 20px;
        }

        .word-cloud-item.size-5 {
            font-size: 22px;
            font-weight: bold;
        }
    </style>
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">نظرسنجی‌ها</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.survey.edit', $survey->id) }}">{{ $survey->title }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.report.index', $survey->id) }}">گزارش</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.report.summary', $survey->id) }}">خلاصه
                        نتایج</a></li>
                <li class="breadcrumb-item active">گزارش سوال</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <!-- Question info card -->
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="mb-3 mb-md-0">
                            <div class="d-flex align-items-center mb-2">
                                <h4 class="mb-0 me-2">{{ $question->question_text }}</h4>
                                @if($question->is_required)
                                    <span class="badge bg-danger">اجباری</span>
                                @else
                                    <span class="badge bg-info">اختیاری</span>
                                @endif
                            </div>
                            <p class="text-muted mb-0">
                                نوع سوال: <span class="fw-bold">{{ $questionData['type_name'] }}</span> |
                                تعداد پاسخ‌ها: <span class="fw-bold">{{ $questionData['total_answers'] }}</span> |
                                نرخ پاسخدهی: <span class="fw-bold">{{ $questionData['response_rate'] }}%</span>
                            </p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.survey.report.summary', $survey->id) }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-right me-1"></i> بازگشت به خلاصه نتایج
                            </a>
                            <a href="{{ route('admin.survey.report.export', $survey->id) }}?question={{ $question->id }}"
                               class="btn btn-primary">
                                <i class="fa fa-file-excel me-1"></i> خروجی اکسل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content based on question type -->
        @if($question->question_type == QuestionTypeEnum::Text)
            <!-- Text question report -->
            @include('survey::admin.report.part.text')
        @endif

        @if($question->question_type == QuestionTypeEnum::Multiple || $question->question_type == QuestionTypeEnum::Single)
            <!-- Choice question report -->
            @include('survey::admin.report.part.option')
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

            @if($question->question_type != QuestionTypeEnum::Text && isset($questionData['options']) && count($questionData['options']) > 0)
            // Prepare data for charts
            const labels = [@foreach($questionData['options'] as $option) '{{ $option['text'] }}', @endforeach];
            const data = [@foreach($questionData['options'] as $option) {{ $option['count'] }}, @endforeach];
            const colors = [@foreach($questionData['options'] as $option) '{{ $option['color'] }}', @endforeach];

            // Pie chart
            const ctx = document.getElementById('optionsChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 15,
                                padding: 15
                            }
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

            // Bar chart
            const barCtx = document.getElementById('optionsBarChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'تعداد پاسخ‌ها',
                        data: data,
                        backgroundColor: colors,
                        borderWidth: 1
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
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.dataset.label || '';
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
            @endif

            @if(isset($questionData['monthly_responses']) && count($questionData['monthly_responses']) > 0)
            // Monthly trend chart
            const trendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
            const trendData = {
                labels: [@foreach($questionData['monthly_responses'] as $month => $count) '{{ $month }}', @endforeach],
                datasets: [{
                    label: 'تعداد پاسخ‌ها',
                    data: [@foreach($questionData['monthly_responses'] as $month => $count) {{ $count }}, @endforeach],
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
        });
    </script>
@endsection
