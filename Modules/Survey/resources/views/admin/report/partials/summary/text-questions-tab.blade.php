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