<div class="col-xl-8">
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">توزیع پاسخ‌ها</h3>
        </div>
        <div class="card-body">
            @if(isset($questionData['options']) && count($questionData['options']) > 0)
                <div class="row">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="optionsChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="optionsBarChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                        <tr>
                            <th>گزینه</th>
                            <th class="text-center">تعداد</th>
                            <th class="text-center">درصد</th>
                            <th>نمودار</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($questionData['options'] as $option)
                            <tr>
                                <td>
                                                    <span class="option-badge"
                                                          style="background-color: {{ $option['color'] }}">
                                                        {{ $option['text'] }}
                                                    </span>
                                </td>
                                <td class="text-center">{{ $option['count'] }}</td>
                                <td class="text-center">{{ $option['percentage'] }}%</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar"
                                             style="width: {{ $option['percentage'] }}%; background-color: {{ $option['color'] }};"
                                             role="progressbar"
                                             aria-valuenow="{{ $option['percentage'] }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                            {{ $option['percentage'] }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-1"></i>
                    هیچ پاسخی برای این سوال ثبت نشده است.
                </div>
            @endif
        </div>
    </div>

    <!-- Monthly trend -->
    @if(isset($questionData['monthly_responses']) && count($questionData['monthly_responses']) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">روند پاسخ‌ها در طول زمان</h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="col-xl-4">
    <!-- Question stats -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">آمار کلی سوال</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-xl-12">
                    <div class="card bg-primary text-white stats-card mb-4">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fa fa-users fa-2x opacity-75"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $questionData['total_answers'] }}</h5>
                                    <p class="mb-0 opacity-75">پاسخ‌های دریافتی</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-12">
                    <div class="card bg-success text-white stats-card mb-4">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fa fa-chart-pie fa-2x opacity-75"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $questionData['response_rate'] }}%</h5>
                                    <p class="mb-0 opacity-75">نرخ پاسخدهی</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="mt-2 mb-3">جزئیات بیشتر</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>نوع سوال:</span>
                    <span class="fw-bold">{{ $questionData['type_name'] }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>وضعیت:</span>
                    <span class="fw-bold">
                                    @if($question->is_required)
                            <span class="badge bg-danger">اجباری</span>
                        @else
                            <span class="badge bg-info">اختیاری</span>
                        @endif
                                </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>ترتیب نمایش:</span>
                    <span class="fw-bold">{{ $question->order }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>تعداد گزینه‌ها:</span>
                    <span class="fw-bold">{{ $question->options->count() }}</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Most popular option -->
    @if(isset($questionData['options']) && count($questionData['options']) > 0)
        @php
            $mostPopular = collect($questionData['options'])->sortByDesc('count')->first();
            $leastPopular = collect($questionData['options'])->sortBy('count')->first();
        @endphp

        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">محبوب‌ترین و کم‌طرفدارترین گزینه‌ها</h3>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="mb-3">محبوب‌ترین گزینه:</h6>
                    <div class="d-flex align-items-center mb-2">
                                    <span class="option-badge" style="background-color: {{ $mostPopular['color'] }}">
                                        {{ $mostPopular['text'] }}
                                    </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>تعداد انتخاب:</span>
                        <span class="fw-bold">{{ $mostPopular['count'] }} ({{ $mostPopular['percentage'] }}%)</span>
                    </div>
                </div>

                <div>
                    <h6 class="mb-3">کم‌طرفدارترین گزینه:</h6>
                    <div class="d-flex align-items-center mb-2">
                                    <span class="option-badge" style="background-color: {{ $leastPopular['color'] }}">
                                        {{ $leastPopular['text'] }}
                                    </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>تعداد انتخاب:</span>
                        <span class="fw-bold">{{ $leastPopular['count'] }} ({{ $leastPopular['percentage'] }}%)</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
