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