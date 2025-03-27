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
                            <canvas id="choiceQuestionChart{{ $question['id'] }}" width="400" height="250"></canvas>
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
                                                <span class="option-badge" style="background-color: {{ $option['color'] }}">
                                                    {{ $option['text'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $option['count'] }}</td>
                                            <td class="text-center">{{ $option['percentage'] }}%</td>
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