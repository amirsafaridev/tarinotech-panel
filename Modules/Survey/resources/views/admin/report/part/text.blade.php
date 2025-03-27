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
                            {{ $word['word'] }} <span
                                class="badge bg-light text-dark">{{ $word['count'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
