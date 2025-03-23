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
        .question-card {
            margin-bottom: 30px;
            border-right: 4px solid #5d87ff;
            transition: all 0.3s ease;
        }

        .question-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            position: relative;
            height: 250px;
            margin-top: 15px;
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

        .text-answer {
            border-right: 3px solid #5d87ff;
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .nav-link.active {
            background-color: #5d87ff !important;
            color: white !important;
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
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">نظرسنجی‌ها</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.survey.edit', $survey->id) }}">{{ $survey->title }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.survey.report.index', $survey->id) }}">گزارش</a>
                </li>
                <li class="breadcrumb-item active">خلاصه نتایج</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <!-- Report actions -->
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="mb-3 mb-md-0">
                            <h4 class="mb-1">خلاصه نتایج نظرسنجی</h4>
                            <p class="text-muted mb-0">
                                تعداد کل پاسخ‌ها: <span class="fw-bold">{{ $stats['total_responses'] }}</span> |
                                نرخ تکمیل: <span class="fw-bold">{{ $stats['completion_rate'] }}%</span>
                            </p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.survey.report.index', $survey->id) }}" class="btn btn-secondary">
                                <i class="fa fa-dashboard me-1"></i> داشبورد
                            </a>
                            <a href="{{ route('admin.survey.report.charts', $survey->id) }}" class="btn btn-success">
                                <i class="fa fa-chart-pie me-1"></i> نمودارها
                            </a>
                            <a href="{{ route('admin.survey.report.export', $survey->id) }}" class="btn btn-primary">
                                <i class="fa fa-file-excel me-1"></i> خروجی اکسل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation tabs -->
        <div class="col-xl-12 mb-4">
            <ul class="nav nav-tabs nav-justified" id="questionTypeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-questions"
                            type="button" role="tab" aria-controls="all-questions" aria-selected="true">
                        <i class="fa fa-list me-1"></i> همه سوالات
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="choice-tab" data-bs-toggle="tab" data-bs-target="#choice-questions"
                            type="button" role="tab" aria-controls="choice-questions" aria-selected="false">
                        <i class="fa fa-check-circle me-1"></i> سوالات انتخابی
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="text-tab" data-bs-toggle="tab" data-bs-target="#text-questions"
                            type="button" role="tab" aria-controls="text-questions" aria-selected="false">
                        <i class="fa fa-align-left me-1"></i> سوالات متنی
                    </button>
                </li>
            </ul>
        </div>

        <!-- Questions summary -->
        <div class="col-xl-12">
            <div class="tab-content" id="questionTabContent">
                <!-- All questions tab -->
                <div class="tab-pane fade show active" id="all-questions" role="tabpanel" aria-labelledby="all-tab">
                    @if(empty($questionsSummary))
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            هنوز داده‌ای برای نمایش وجود ندارد.
                        </div>
                    @else
                        @foreach($questionsSummary as $index => $question)
                            <div class="card question-card mb-4" id="question-{{ $question['id'] }}">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <span class="badge bg-secondary me-2">{{ $index + 1 }}</span>
                                        {{ $question['text'] }}
                                        @if($question['is_required'])
                                            <span class="badge bg-danger ms-2">اجباری</span>
                                        @endif
                                    </h5>
                                    <div>
                                        <span class="badge bg-primary">{{ $question['type_name'] }}</span>
                                        <a href="{{ route('admin.survey.report.question', [$survey->id, $question['id']]) }}"
                                           class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="fa fa-chart-line"></i> گزارش تفصیلی
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>تعداد پاسخ‌ها:</span>
                                                <span class="fw-bold">{{ $question['total_answers'] }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>نرخ پاسخدهی:</span>
                                                <span class="fw-bold">{{ $question['response_rate'] }}%</span>
                                            </div>
                                            <div class="progress mb-4" style="height: 15px;">
                                                <div
                                                    class="progress-bar {{ $question['response_rate'] < 40 ? 'bg-danger' : ($question['response_rate'] < 70 ? 'bg-warning' : 'bg-success') }}"
                                                    role="progressbar"
                                                    style="width: {{ $question['response_rate'] }}%;"
                                                    aria-valuenow="{{ $question['response_rate'] }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{ $question['response_rate'] }}%
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            @if(in_array($question['type'], [1, 2]))
                                                <!-- Single or Multiple choice -->
                                                @if(isset($question['options']) && count($question['options']) > 0)
                                                    <div class="chart-container">
                                                        <canvas id="questionChart{{ $question['id'] }}" width="400"
                                                                height="250"></canvas>
                                                    </div>
                                                    <div class="mt-3">
                                                        @foreach($question['options'] as $option)
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-2">
                                                                <div>
                                                                    <span class="option-badge"
                                                                          style="background-color: {{ $option['color'] }}">
                                                                        {{ $option['text'] }}
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <span class="fw-bold">{{ $option['count'] }}</span>
                                                                    <span class="text-muted">({{ $option['percentage'] }}%)</span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning">
                                                        <i class="fa fa-exclamation-circle me-1"></i>
                                                        هیچ پاسخی برای این سوال ثبت نشده است.
                                                    </div>
                                                @endif
                                            @elseif($question['type'] == 3)
                                                <!-- Text -->
                                                @if(isset($question['text_samples']) && count($question['text_samples']) > 0)
                                                    <h6 class="mb-3">نمونه پاسخ‌های متنی:</h6>
                                                    @foreach($question['text_samples'] as $textAnswer)
                                                        <div class="text-answer">
                                                            "{{ $textAnswer }}"
                                                        </div>
                                                    @endforeach
                                                    <a href="{{ route('admin.survey.report.question', [$survey->id, $question['id']]) }}"
                                                       class="btn btn-sm btn-outline-primary mt-2">
                                                        <i class="fa fa-eye"></i> مشاهده همه پاسخ‌ها
                                                    </a>
                                                @else
                                                    <div class="alert alert-warning">
                                                        <i class="fa fa-exclamation-circle me-1"></i>
                                                        هیچ پاسخ متنی برای این سوال ثبت نشده است.
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Choice questions tab -->
                <div class="tab-pane fade" id="choice-questions" role="tabpanel" aria-labelledby="choice-tab">
                    @php
                        $choiceQuestions = collect($questionsSummary)->filter(function($q) {
                            return in_array($q['type'], [1, 2]); // Single or Multiple choice
                        });
                    @endphp

                    @if($choiceQuestions->isEmpty())
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            هیچ سوال انتخابی (تک گزینه‌ای یا چند گزینه‌ای) در این نظرسنجی وجود ندارد.
                        </div>
                    @else
                        @foreach($choiceQuestions as $index => $question)
                            <div class="card question-card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <span class="badge bg-secondary me-2">{{ $index + 1 }}</span>
                                        {{ $question['text'] }}
                                        @if($question['is_required'])
                                            <span class="badge bg-danger ms-2">اجباری</span>
                                        @endif
                                    </h5>
                                    <div>
                                        <span class="badge bg-primary">{{ $question['type_name'] }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="chart-container">
                                                <canvas id="choiceQuestionChart{{ $question['id'] }}" width="400"
                                                        height="250"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            @if(isset($question['options']) && count($question['options']) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>گزینه</th>
                                                            <th class="text-center">تعداد</th>
                                                            <th class="text-center">درصد</th>
                                                            <th class="text-center">نمودار</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($question['options'] as $option)
                                                            <tr>
                                                                <td>
                                                                        <span class="option-badge"
                                                                              style="background-color: {{ $option['color'] }}">
                                                                            {{ $option['text'] }}
                                                                        </span>
                                                                </td>
                                                                <td class="text-center">{{ $option['count'] }}</td>
                                                                <td class="text-center">{{ $option['percentage'] }}%
                                                                </td>
                                                                <td>
                                                                    <div class="progress" style="height: 15px;">
                                                                        <div class="progress-bar"
                                                                             role="progressbar"
                                                                             style="width: {{ $option['percentage'] }}%; background-color: {{ $option['color'] }};"
                                                                             aria-valuenow="{{ $option['percentage'] }}"
                                                                             aria-valuemin="0"
                                                                             aria-valuemax="100">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="alert alert-warning">
                                                    <i class="fa fa-exclamation-circle me-1"></i>
                                                    هیچ پاسخی برای این سوال ثبت نشده است.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <a href="{{ route('admin.survey.report.question', [$survey->id, $question['id']]) }}"
                                       class="btn btn-primary">
                                        <i class="fa fa-chart-line me-1"></i> گزارش تفصیلی
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Text questions tab -->
                <div class="tab-pane fade" id="text-questions" role="tabpanel" aria-labelledby="text-tab">
                    @php
                        $textQuestions = collect($questionsSummary)->filter(function($q) {
                            return $q['type'] == 3; // Text questions
                        });
                    @endphp

                    @if($textQuestions->isEmpty())
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            هیچ سوال متنی در این نظرسنجی وجود ندارد.
                        </div>
                    @else
                        @foreach($textQuestions as $index => $question)
                            <div class="card question-card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <span class="badge bg-secondary me-2">{{ $index + 1 }}</span>
                                        {{ $question['text'] }}
                                        @if($question['is_required'])
                                            <span class="badge bg-danger ms-2">اجباری</span>
                                        @endif
                                    </h5>
                                    <div>
                                        <span class="badge bg-primary">{{ $question['type_name'] }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>تعداد پاسخ‌ها:</span>
                                                <span class="fw-bold">{{ $question['total_answers'] }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>نرخ پاسخدهی:</span>
                                                <span class="fw-bold">{{ $question['response_rate'] }}%</span>
                                            </div>
                                            <div class="progress mb-4" style="height: 15px;">
                                                <div
                                                    class="progress-bar {{ $question['response_rate'] < 40 ? 'bg-danger' : ($question['response_rate'] < 70 ? 'bg-warning' : 'bg-success') }}"
                                                    role="progressbar"
                                                    style="width: {{ $question['response_rate'] }}%;"
                                                    aria-valuenow="{{ $question['response_rate'] }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{ $question['response_rate'] }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h6 class="mb-3">نمونه پاسخ‌های متنی:</h6>
                                    @if(isset($question['text_samples']) && count($question['text_samples']) > 0)
                                        @foreach($question['text_samples'] as $textAnswer)
                                            <div class="text-answer">
                                                "{{ $textAnswer }}"
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fa fa-exclamation-circle me-1"></i>
                                            هیچ پاسخ متنی برای این سوال ثبت نشده است.
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer text-end">
                                    <a href="{{ route('admin.survey.report.question', [$survey->id, $question['id']]) }}"
                                       class="btn btn-primary">
                                        <i class="fa fa-eye me-1"></i> مشاهده همه پاسخ‌ها
                                    </a>
                                </div>
                            </div>
                        @endforeach
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

            // Generate charts for choice questions
            @foreach($questionsSummary as $question)
            @if(in_array($question['type'], [1, 2]) && isset($question['options']) && count($question['options']) > 0)
            // Prepare data for charts
            const labels{{ $question['id'] }} = [@foreach($question['options'] as $option) '{{ $option['text'] }}', @endforeach];
            const data{{ $question['id'] }} = [@foreach($question['options'] as $option) {{ $option['count'] }}, @endforeach];
            const colors{{ $question['id'] }} = [@foreach($question['options'] as $option) '{{ $option['color'] }}', @endforeach];

            // Create chart for all questions tab
            const ctx{{ $question['id'] }} = document.getElementById('questionChart{{ $question['id'] }}').getContext('2d');
            new Chart(ctx{{ $question['id'] }}, {
                type: 'pie',
                data: {
                    labels: labels{{ $question['id'] }},
                    datasets: [{
                        data: data{{ $question['id'] }},
                        backgroundColor: colors{{ $question['id'] }},
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

            // Create chart for choice questions tab
            const choiceCtx{{ $question['id'] }} = document.getElementById('choiceQuestionChart{{ $question['id'] }}');
            if (choiceCtx{{ $question['id'] }}) {
                new Chart(choiceCtx{{ $question['id'] }}, {
                    type: 'doughnut',
                    data: {
                        labels: labels{{ $question['id'] }},
                        datasets: [{
                            data: data{{ $question['id'] }},
                            backgroundColor: colors{{ $question['id'] }},
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 10
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
            }
            @endif
            @endforeach
        });
    </script>
@endsection
