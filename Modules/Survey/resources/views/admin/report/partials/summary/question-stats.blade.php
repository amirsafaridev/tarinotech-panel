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