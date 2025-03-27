<!-- Report actions -->
<div class="col-xl-12">
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="mb-3 mb-md-0">
                    <h4 class="mb-1">خلاصه نتایج نظرسنجی</h4>
                    <p class="text-muted mb-0">
                        تعداد کل پاسخ‌ها: <span class="fw-bold">{{ $stats['total_responses'] }}</span> |
                        نرخ تکمیل: <span class="fw-bold">{{ $stats['completion_rate'] }}%</span>
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.survey.report.index', $survey->id) }}" class="btn btn-secondary">
                        <i class="fa fa-dashboard me-1"></i> داشبورد
                    </a>
                    <a href="{{ route('admin.survey.report.charts', $survey->id) }}" class="btn btn-success">
                        <i class="fa fa-chart-pie me-1"></i> نمودارها
                    </a>
                    <a href="{{ route('admin.survey.report.export', $survey->id) }}" class="btn btn-primary">
                        <i class="fa fa-file-excel me-1"></i> خروجی اکسل
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>