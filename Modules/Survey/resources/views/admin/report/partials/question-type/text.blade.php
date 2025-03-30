<!-- Text Question Report -->
<div class="col-xl-12">
    @if($question->question_type == \Modules\Survey\app\Enums\Database\QuestionTypeEnum::ShortText && isset($questionData['settings']))
    <div class="alert alert-info mb-4">
        <div class="d-flex">
            <span class="me-2"><i class="fa fa-info-circle"></i></span>
            <div>
                <strong>تنظیمات متن کوتاه:</strong>
                @if(isset($questionData['settings']['minLength']))
                    حداقل {{ $questionData['settings']['minLength'] }} کاراکتر •
                @endif
                @if(isset($questionData['settings']['maxLength']))
                    حداکثر {{ $questionData['settings']['maxLength'] }} کاراکتر •
                @endif
                @if(isset($questionData['settings']['placeholder']))
                    دارای متن راهنما
                @endif
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <!-- Text answer statistics -->
        <div class="col-xl-4 col-md-6">
            <div class="card stats-card bg-light-primary mb-4">
                <div class="card-body">
                    <h5 class="card-title">آمار پاسخ‌های متنی</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0">
                            <span>تعداد کل پاسخ‌ها:</span>
                            <span class="fw-bold">{{ $questionData['total_answers'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0">
                            <span>میانگین تعداد کلمات:</span>
                            <span class="fw-bold">{{ $questionData['text_stats']['avg_words'] ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0">
                            <span>طولانی‌ترین پاسخ:</span>
                            <span class="fw-bold">{{ $questionData['text_stats']['max_words'] ?? 'N/A' }} کلمه</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0">
                            <span>کوتاه‌ترین پاسخ:</span>
                            <span class="fw-bold">{{ $questionData['text_stats']['min_words'] ?? 'N/A' }} کلمه</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Monthly trend -->
        <div class="col-xl-8 col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">روند پاسخدهی ماهانه</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container-small">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Word cloud and common words -->
    @if(isset($questionData['text_words']) && count($questionData['text_words']) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">کلمات پرتکرار</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="word-cloud mb-4">
                            @foreach($questionData['text_words'] as $word)
                                <span class="word-cloud-item size-{{ $word['size'] }}">
                                    {{ $word['text'] }} <small>({{ $word['count'] }})</small>
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>کلمه</th>
                                    <th class="text-center">تعداد تکرار</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($questionData['text_words'] as $word)
                                    @if($loop->index < 10)
                                        <tr>
                                            <td>{{ $word['text'] }}</td>
                                            <td class="text-center">{{ $word['count'] }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Text responses -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">پاسخ‌های متنی</h5>
        </div>
        <div class="card-body">
            @if(isset($questionData['text_answers']) && count($questionData['text_answers']) > 0)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>نمایش {{ min(20, $questionData['total_answers']) }} پاسخ از {{ $questionData['total_answers'] }} پاسخ</span>
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-download me-1"></i> دانلود همه پاسخ‌ها
                    </a>
                </div>
                @foreach($questionData['text_answers'] as $answer)
                    <div class="text-answer">
                        <div class="mb-1 text-muted small">
                            <i class="fa fa-calendar me-1"></i> {{ $answer['date'] }}
                            @if(isset($answer['user_name']))
                                | <i class="fa fa-user me-1"></i> {{ $answer['user_name'] }}
                            @endif
                        </div>
                        {{ $answer['text'] }}
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-1"></i>
                    هیچ پاسخی برای این سوال متنی ثبت نشده است.
                </div>
            @endif
        </div>
    </div>
</div>
