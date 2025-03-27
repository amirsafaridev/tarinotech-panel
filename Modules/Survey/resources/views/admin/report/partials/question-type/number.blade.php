<!-- Number Question Report -->
<div class="col-xl-12">
    <div class="row">
        <!-- Number statistics -->
        <div class="col-xl-4 col-md-6">
            <div class="card stats-card bg-light-primary mb-4">
                <div class="card-body">
                    <h5 class="card-title">آمار توصیفی</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0">
                            <span>تعداد کل پاسخ‌ها:</span>
                            <span class="fw-bold">{{ $questionData['total_answers'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0">
                            <span>حداقل:</span>
                            <span class="fw-bold">{{ $questionData['number_stats']['min'] ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0">
                            <span>حداکثر:</span>
                            <span class="fw-bold">{{ $questionData['number_stats']['max'] ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0">
                            <span>میانگین:</span>
                            <span class="fw-bold">{{ $questionData['number_stats']['avg'] ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0">
                            <span>میانه:</span>
                            <span class="fw-bold">{{ $questionData['number_stats']['median'] ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0">
                            <span>انحراف معیار:</span>
                            <span class="fw-bold">{{ $questionData['number_stats']['std_dev'] ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Number distribution -->
        <div class="col-xl-8 col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">توزیع پاسخ‌های عددی</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="numberDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly trend -->
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

    <!-- Number responses details -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">جزئیات پاسخ‌های عددی</h5>
        </div>
        <div class="card-body">
            @if(isset($questionData['number_distribution']) && count($questionData['number_distribution']) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>محدوده</th>
                            <th class="text-center">تعداد پاسخ‌ها</th>
                            <th class="text-center">درصد</th>
                            <th class="text-center">نمودار</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($questionData['number_distribution'] as $bucket)
                            <tr>
                                <td>{{ $bucket['range'] }}</td>
                                <td class="text-center">{{ $bucket['count'] }}</td>
                                <td class="text-center">{{ $bucket['percentage'] }}%</td>
                                <td>
                                    <div class="progress" style="height: 15px;">
                                        <div class="progress-bar bg-primary"
                                             role="progressbar"
                                             style="width: {{ $bucket['percentage'] }}%;"
                                             aria-valuenow="{{ $bucket['percentage'] }}"
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
                    هیچ داده‌ای برای نمایش توزیع عددی وجود ندارد.
                </div>
            @endif
        </div>
    </div>
</div>