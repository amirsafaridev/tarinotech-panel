<!-- Option Question Report (Single or Multiple Choice) -->
<div class="col-xl-12">
    <div class="row">
        <!-- Charts -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">نمودار دایره‌ای</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="optionsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">نمودار میله‌ای</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="optionsBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Options details -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">جزئیات گزینه‌ها</h5>
        </div>
        <div class="card-body">
            @if(isset($questionData['options']) && count($questionData['options']) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>گزینه</th>
                            <th class="text-center">تعداد پاسخ‌ها</th>
                            <th class="text-center">درصد</th>
                            <th class="text-center">نمودار</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($questionData['options'] as $option)
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
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-1"></i>
                    هیچ پاسخی برای این سوال ثبت نشده است.
                </div>
            @endif
        </div>
    </div>

    <!-- Monthly trend -->
    <div class="card">
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