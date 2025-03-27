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