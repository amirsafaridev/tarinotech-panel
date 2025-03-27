<!-- Single or Multiple choice -->
@if(isset($question['options']) && count($question['options']) > 0)
    <div class="chart-container">
        <canvas id="questionChart{{ $question['id'] }}" width="400" height="250"></canvas>
    </div>
    <div class="mt-3">
        @foreach($question['options'] as $option)
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <span class="option-badge" style="background-color: {{ $option['color'] }}">
                        {{ $option['text'] }}
                    </span>
                </div>
                <div>
                    <span class="fw-bold">{{ $option['count'] }}</span>
                    <span class="text-muted">({{ $option['percentage'] }}%)</span>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-warning">
        <i class="fa fa-exclamation-circle me-1"></i>
        هیچ پاسخی برای این سوال ثبت نشده است.
    </div>
@endif