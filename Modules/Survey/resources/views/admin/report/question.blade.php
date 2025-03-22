@extends('admin.master')
@section('title') {{ $title }} @endsection
@section('head')
    @include('admin.partial.loader.style',['load'=>[
        \App\Enums\Assets\StyleLoader::Toast(),
        /*\App\Enums\Assets\StyleLoader::Chart(),*/
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
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .text-answer {
            background-color: #f9f9f9;
            border-right: 3px solid #5d87ff;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .text-answer:hover {
            background-color: #f0f0f0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
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
        .word-cloud-item.size-1 { font-size: 14px; opacity: 0.7; }
        .word-cloud-item.size-2 { font-size: 16px; opacity: 0.8; }
        .word-cloud-item.size-3 { font-size: 18px; opacity: 0.9; }
        .word-cloud-item.size-4 { font-size: 20px; }
        .word-cloud-item.size-5 { font-size: 22px; font-weight: bold; }
    </style>
@endsection
@section('content')

    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('panel.dashboard.title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">نظرسنجی‌ها</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.edit', $survey->id) }}">{{ $survey->title }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.report.index', $survey->id) }}">گزارش</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.report.summary', $survey->id) }}">خلاصه نتایج</a></li>
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
                            <a href="{{ route('admin.survey.report.export', $survey->id) }}?question={{ $question->id }}" class="btn btn-primary">
                                <i class="fa fa-file-excel me-1"></i> خروجی اکسل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content based on question type -->
        @if($question->question_type == \Modules\Survey\app\Enums\Database\QuestionTypeEnum::Text)
            <!-- Text question report -->
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">پاسخ‌های متنی</h3>
                    </div>
                    <div class="card-body">
                        @if(isset($questionData['text_answers']) && $questionData['text_answers']->count() > 0)
                            @foreach($questionData['text_answers'] as $textAnswer)
                                <div class="text-answer">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">
                                            {{ $textAnswer->response->respondent_name ?? 'بدون نام' }}
                                            ({{ $textAnswer->created_at->toJalali()->format(formatJalaliDateTime()) }})
                                        </small>
                                        <span class="badge bg-light text-dark">
                                            IP: {{ $textAnswer->response->ip_address ?? 'نامشخص' }}
                                        </span>
                                    </div>
                                    <p class="mb-0 mt-1">{{ $textAnswer->answer_text }}</p>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-center mt-4">
                                {{ $questionData['text_answers']->links() }}
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-1"></i>
                                هیچ پاسخ متنی برای این سوال ثبت نشده است.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <!-- Text analysis stats -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">آمار کلی پاسخ‌ها</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>تعداد کل پاسخ‌ها:</span>
                                <span class="fw-bold">{{ $questionData['total_answers'] }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>نرخ پاسخدهی:</span>
                                <span class="fw-bold">{{ $questionData['response_rate'] }}%</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>وضعیت سوال:</span>
                                <span class="fw-bold">
                                    @if($question->is_required)
                                        <span class="badge bg-danger">اجباری</span>
                                    @else
                                        <span class="badge bg-info">اختیاری</span>
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Word cloud -->
                @if(isset($questionData['word_frequency']) && count($questionData['word_frequency']) > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">تحلیل کلمات پرتکرار</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-center">
                                @foreach($questionData['word_frequency'] as $index => $word)
                                    @php
                                        $maxCount = $questionData['word_frequency'][0]['count'];
                                        $minCount = end($questionData['word_frequency'])['count'];
                                        $range = max(1, $maxCount - $minCount);
                                        $sizeClass = ceil(min(5, max(1, (($word['count'] - $minCount) / $range) * 5)));
                                    @endphp
                                    <div class="word-cloud-item size-{{ $sizeClass }}">
                                        {{ $word['word'] }} <span class="badge bg-light text-dark">{{ $word['count'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Choice question report -->
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">توزیع پاسخ‌ها</h3>
                    </div>
                    <div class="card-body">
                        @if(isset($questionData['options']) && count($questionData['options']) > 0)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="optionsChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="optionsBarChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                    <tr>
                                        <th>گزینه</th>
                                        <th class="text-center">تعداد</th>
                                        <th class="text-center">درصد</th>
                                        <th>نمودار</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($questionData['options'] as $option)
                                        <tr>
                                            <td>
                                                    <span class="option-badge" style="background-color: {{ $option['color'] }}">
                                                        {{ $option['text'] }}
                                                    </span>
                                            </td>
                                            <td class="text-center">{{ $option['count'] }}</td>
                                            <td class="text-center">{{ $option['percentage'] }}%</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar"
                                                         style="width: {{ $option['percentage'] }}%; background-color: {{ $option['color'] }};"
                                                         role="progressbar"
                                                         aria-valuenow="{{ $option['percentage'] }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100">
                                                        {{ $option['percentage'] }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-1"></i>
                                هیچ پاسخی برای این سوال ثبت نشده است.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Monthly trend -->
                @if(isset($questionData['monthly_responses']) && count($questionData['monthly_responses']) > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">روند پاسخ‌ها در طول زمان</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="monthlyTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-xl-4">
                <!-- Question stats -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">آمار کلی سوال</h3>
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
                                                <h5 class="mb-0">{{ $questionData['total_answers'] }}</h5>
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
                                                <h5 class="mb-0">{{ $questionData['response_rate'] }}%</h5>
                                                <p class="mb-0 opacity-75">نرخ پاسخدهی</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-2 mb-3">جزئیات بیشتر</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>نوع سوال:</span>
                                <span class="fw-bold">{{ $questionData['type_name'] }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>وضعیت:</span>
                                <span class="fw-bold">
                                    @if($question->is_required)
                                        <span class="badge bg-danger">اجباری</span>
                                    @else
                                        <span class="badge bg-info">اختیاری</span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>ترتیب نمایش:</span>
                                <span class="fw-bold">{{ $question->order }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>تعداد گزینه‌ها:</span>
                                <span class="fw-bold">{{ $question->options->count() }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Most popular option -->
                @if(isset($questionData['options']) && count($questionData['options']) > 0)
                    @php
                        $mostPopular = collect($questionData['options'])->sortByDesc('count')->first();
                        $leastPopular = collect($questionData['options'])->sortBy('count')->first();
                    @endphp

                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">محبوب‌ترین و کم‌طرفدارترین گزینه‌ها</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="mb-3">محبوب‌ترین گزینه:</h6>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="option-badge" style="background-color: {{ $mostPopular['color'] }}">
                                        {{ $mostPopular['text'] }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>تعداد انتخاب:</span>
                                    <span class="fw-bold">{{ $mostPopular['count'] }} ({{ $mostPopular['percentage'] }}%)</span>
                                </div>
                            </div>

                            <div>
                                <h6 class="mb-3">کم‌طرفدارترین گزینه:</h6>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="option-badge" style="background-color: {{ $leastPopular['color'] }}">
                                        {{ $leastPopular['text'] }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>تعداد انتخاب:</span>
                                    <span class="fw-bold">{{ $leastPopular['count'] }} ({{ $leastPopular['percentage'] }}%)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

@endsection
@section('script')
    @include('admin.partial.loader.script',['load'=>[
        \App\Enums\Assets\ScriptLoader::Chart(),
    ]])
    <script>
        $(document).ready(function() {
            activeParentUl('{{ route('admin.survey.index') }}');

            @if($question->question_type != \Modules\Survey\app\Enums\Database\QuestionTypeEnum::Text && isset($questionData['options']) && count($questionData['options']) > 0)
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
                                label: function(context) {
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
                                label: function(context) {
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
