<!-- Number -->
@if(isset($question['number_stats']))
    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <canvas id="numberChart{{ $question['id'] }}" width="400" height="250"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">آمار توصیفی</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>حداقل:</span>
                            <span class="fw-bold">{{ $question['number_stats']['min'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>حداکثر:</span>
                            <span class="fw-bold">{{ $question['number_stats']['max'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>میانگین:</span>
                            <span class="fw-bold">{{ $question['number_stats']['avg'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>میانه:</span>
                            <span class="fw-bold">{{ $question['number_stats']['median'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-warning">
        <i class="fa fa-exclamation-circle me-1"></i>
        هیچ اطلاعات آماری برای این سوال عددی ثبت نشده است.
    </div>
@endif