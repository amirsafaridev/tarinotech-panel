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
                <td>{{ $project->target->agreement_duration }}</td>
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
                <td>{{ $project->target->price_monthly }}</td>
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