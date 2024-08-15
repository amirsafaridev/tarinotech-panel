<div class="card">
    <div class="card-header">
        <h3 class="card-title">سئو</h3>
        <div class="card-options">
            <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                        class="fal fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <tbody>
            <tr>
                <td>شناسه</td>
                <td>{{ $project->target->id }}</td>
            </tr>

            @if($project->target->package)
                <tr>
                    <td>پکیج</td>
                    <td>{{ $project->target->package->title }}</td>
                </tr>

                <tr>
                    <td>قیمت پکیج پروژه در زمان عقد قرارداد (ریال)</td>
                    <td>
                        @php
                            $packagePrice = 0;
                            if ($project->agreement_at) {
                                $packagePriceResult = $project->target->package->getPriceForDate($project->agreement_at);
                                if ($packagePriceResult && $packagePriceResult->getPrice()) {
                                    $packagePrice = $packagePriceResult->getPrice()->price;
                                }
                            }
                        @endphp
                        {{ number_format($packagePrice) }}
                    </td>
                </tr>
            @endif


            <tr>
                <td>زمینه فعالیت</td>
                <td>{{ $project->target->field_activity }}</td>
            </tr>

            <!-- Host -->
            @if(isset($project->target->host['host_location']))
                <tr>
                    <td>هاست</td>
                    <td>{{ \Modules\Project\app\Enums\SeoHostLocation::getDescription($project->target->host['host_location']) }}</td>
                </tr>
            @endif

            @if(!empty($project->target->host['host_provider']))
                <tr>
                    <td>هاستینگ (از چه سایتی خریداری شده؟)</td>
                    <td>{{ $project->target->host['host_provider'] }}</td>
                </tr>
            @endif

            <tr>
                <td>مدت قرارداد</td>
                <td>{{ round($project->target->agreement_duration / 30) }} ماه</td>
            </tr>

            <tr>
                <td>تعداد کلمات سئو شدنی</td>
                <td>{{ $project->target->keywords_count }}</td>
            </tr>

            @if($project->target->keywords)
                <tr>
                    <td>لیست کلمات قراردادی</td>
                    <td>{{ implode(', ',explode(PHP_EOL,$project->target->keywords)) }}</td>
                </tr>
            @endif

            <tr>
                <td>میزان تولید محتوا</td>
                <td>{{ $project->target->amount_content }}</td>
            </tr>

            <tr>
                <td>پرداختی ماهیانه (ریال)</td>
                <td>{{ number_format($project->target->price_monthly) }} ریال</td>
            </tr>

            <tr>
                <td>تاریخ سررسید پرداخت ها (چندم هر ماه)</td>
                <td>{{ $project->target->due_date_payments }}</td>
            </tr>

            <tr>
                <td>طراحی سایت پروژه</td>
                <td>{{ \Modules\Project\app\Enums\ProjectDesignBy::getDescription($project->target->designed_by) }}</td>
            </tr>

            </tbody>
        </table>
    </div>
</div>