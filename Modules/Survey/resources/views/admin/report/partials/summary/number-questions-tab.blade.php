@php
    $numberQuestions = collect($questionsSummary)->filter(function($q) {
        return $q['type'] == 4; // Number questions
    });
@endphp

@if($numberQuestions->isEmpty())
    <div class="alert alert-info">
        <i class="fa fa-info-circle me-1"></i>
        هیچ سوال عددی در این نظرسنجی وجود ندارد.
    </div>
@else
    @foreach($numberQuestions as $index => $question)
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
                        @if(isset($question['number_stats']))
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="numberChart{{ $question['id'] }}" width="400" height="250"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3">آمار توصیفی</h6>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>حداقل:</span>
                                                    <span class="fw-bold">{{ $question['number_stats']['min'] }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>حداکثر:</span>
                                                    <span class="fw-bold">{{ $question['number_stats']['max'] }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>میانگین:</span>
                                                    <span class="fw-bold">{{ $question['number_stats']['avg'] }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>میانه:</span>
                                                    <span class="fw-bold">{{ $question['number_stats']['median'] }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-circle me-1"></i>
                                هیچ اطلاعات آماری برای این سوال عددی ثبت نشده است.
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