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